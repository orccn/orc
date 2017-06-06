<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc mysql数据库驱动
 */
namespace orc\database;
class Oracle extends Driver
{

    protected $exp = [
        'eq' => '=',
        'neq' => '<>',
        'gt' => '>',
        'egt' => '>=',
        'gte' => '>=',
        'lt' => '<',
        'elt' => '<=',
        'lte' => '<=',
        'notlike' => 'NOT LIKE',
        'like' => 'LIKE',
        'in' => 'IN',
        'notin' => 'NOT IN',
        'not in' => 'NOT IN',
        'between' => 'BETWEEN',
        'not between' => 'NOT BETWEEN',
        'notbetween' => 'NOT BETWEEN'
    ];

    protected $selectSql = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%WHERE%%GROUP%%HAVING%%ORDER%';

    protected $sql = '';

    protected $config = [];

    protected $conn = null;

    protected $sth = null;

    protected $bind = [];

    protected $lastInsertID = null;
    
    protected $transTimes = 0;

    public function __construct($config)
    {
        $this->config = $config;
        $this->connect();
    }

    public function connect()
    {
        if (! $this->conn) {
            $this->conn = \oci_connect($this->config['username'], $this->config['password'], $this->config['dbname'],$this->config['charset']);
        }
        return $this->conn;
    }

    /**
     * set分析
     *
     * @access protected
     * @param array $data            
     * @return string
     */
    protected function parseSet($data)
    {
        foreach ($data as $key => $val) {
            $set[] = $this->parseKey($key) . '=' . $this->parseValue($val);
        }
        return ' SET ' . implode(',', $set);
    }

    /**
     * 字段名分析
     *
     * @access protected
     * @param string $key            
     * @return string
     */
    protected function parseKey(&$key)
    {
        return $key;
    }

    /**
     * value分析
     *
     * @access protected
     * @param mixed $value            
     * @return string
     */
    protected function parseValue($value)
    {
        if (is_numeric($value)){
            return $value;
        }
        if (is_array($value)) {
            $value = strtolower($value[0]) == 'exp' ? $value[1] : array_map([$this,'parseValue'], $value);
        }else if (is_string($value)) {
            $value = "'" . $this->escapeString($value) . "'";
        }
        return $value;
    }

    /**
     * field分析
     *
     * @access protected
     * @param mixed $fields            
     * @return string
     */
    protected function parseField($fields)
    {
        if (is_string($fields) && '' !== $fields) {
            $fields = explode(',', $fields);
        }
        if (is_array($fields)) {
            // 完善数组方式传字段名的支持
            // 支持 'field1'=>'field2' 这样的字段别名定义
            $array = [];
            foreach ($fields as $key => $field) {
                if (! is_numeric($key)) $array[] = $this->parseKey($key) . ' AS ' . $this->parseKey($field);
                else $array[] = $this->parseKey($field);
            }
            $fieldsStr = implode(',', $array);
        } else {
            $fieldsStr = '*';
        }
        // TODO 如果是查询全部字段，并且是join的方式，那么就把要查的表加个别名，以免字段被覆盖
        return $fieldsStr;
    }

    /**
     * table分析
     *
     * @access protected
     * @param mixed $table            
     * @return string
     */
    protected function parseTable($table)
    {
        return $table;
    }

    /**
     * where分析
     *
     * @access protected
     * @param mixed $where            
     * @return string
     */
    protected function parseWhere($where)
    {
        $whereStr = $this->buildWhere($where);
        return empty($whereStr) ? '' : ' WHERE ' . $whereStr;
    }
    
    private function buildWhere($where)
    {
        if (! is_array($where)) {
            return $where;
        }
        // 默认进行 AND 运算
        $operate = isset($where['_logic']) ? strtoupper($where['_logic']) : '';
        if (in_array($operate, ['AND','OR','XOR'])) {
            $operate = ' ' . $operate . ' ';
            unset($where['_logic']);
        } else {
            $operate = ' AND ';
        }
        
        $whereStr = [];
        foreach ($where as $field => $val) {
            if (is_numeric($field)) {
                if (is_array($val) && $val) {
                    $whereStr[] = '( ' . $this->buildWhere($val) . ' )';
                }
            } else {
                // 支持 name|title|nickname 方式定义查询字段
                $whereItems = [];
                foreach (explode('|', trim($field)) as $m => $k) {
                    $whereItems[] = $this->parseWhereItem($k, $val);
                }
                $whereStr[] = count($whereItems) > 1 ? '( ' . implode(' OR ', $whereItems) . ' )' : $whereItems[0];
            }
        }
        $whereStr = implode($operate, $whereStr);
        return $whereStr;
    }
    
    // where子单元分析
    protected function parseWhereItem($field, $val, $exp = null)
    {
        if (! is_array($val)) {
            return $field . ($val === null ? ' is null' : ' ' . $this->exp['eq'] . ' ' . $this->parseValue($val));
        }
        //是否是in操作
        $isInCount = 0;
        foreach ($val as $k => $v)  {
            if (!is_string($exp) && is_numeric($k) && ! is_array($v) && (++ $isInCount > 1)) {
                break;
            }
        }
        if ($isInCount > 1) {
            return $field . ' ' . $this->exp['in'] . ' ( ' . implode(',', $this->parseValue(array_unique($val))) . ' )';
        }
        
        // 计算逻辑操作符
        $operate = isset($val['_logic']) ? strtoupper($val['_logic']) : '';
        if (in_array($operate, ['AND','OR','XOR'])) {
            $operate = ' ' . $operate . ' ';
            unset($val['_logic']);
        } else {
            $operate = ' AND ';
        }
        
        $whereItem = [];
        foreach ($val as $k => $v) {
            // 如果表达式为数字
            if (is_numeric($k)) {
                if (is_array($v)) {
                    if (empty($v)) {
                        continue;
                    }
                    $whereItem[] = $this->parseWhereItem($field, $v);
                } else {
                    $whereItem[] = $field . ' ' . $this->exp['eq'] . ' ' . $this->parseValue($v);
                }
            } else {
                $k = $exp ? $exp : strtolower($k);
                if (! isset($this->exp[$k])) {
                    E("where express error:[{$k}] not exists", - 1);
                }
                $fieldExp = $field . ' ' . $this->exp[$k];
                if (preg_match('/^(eq|neq|gt|egt|gte|lt|elt|lte)$/', $k)) { // 比较运算
                    $whereItem[] = $fieldExp . ' ' . $this->parseValue($v);
                } elseif (preg_match('/^(notlike|like)$/', $k)) { // 模糊查找
                    if (is_array($v)) {
                        if (empty($v)) {
                            continue;
                        }
                        $whereItem[] = $this->parseWhereItem($field, $v, $k);
                    } else {
                        $whereItem[] = $fieldExp . ' ' . $this->parseValue($v);
                    }
                } elseif (preg_match('/^(notin|not in|in)$/', $k)) { // IN 运算
                    if (is_string($v)) {
                        $v = explode(',', $v);
                    }
                    $whereItem[] = $fieldExp . ' (' . implode(',', $this->parseValue(array_unique($val))) . ')';
                } elseif (preg_match('/^(notbetween|not between|between)$/', $k)) { // BETWEEN运算
                    if (is_string($v)) {
                        $v = explode(',', $v);
                    }
                    $whereStr .= $fieldExp . ' ' . $this->parseValue($v[0]) . ' AND ' . $this->parseValue($v[1]);
                } 
            }
        }
        $whereStr = '';
        if ($whereItem) {
            $whereStr = implode($operate, $whereItem);
            if (count($whereItem) > 1) {
                $whereStr = '( ' . $whereStr . ' )';
            }
        }
        return $whereStr;
    }

    /**
     * limit分析
     *
     * @access protected
     * @param mixed $lmit            
     * @return string
     */
    protected function parseLimit($sql,$limit)
    {
        $limit = explode(',', $limit);
        if (!isset($limit[1])){
            $limit = [0,$limit[0]];
        }
        $end = $limit[0] + $limit[1];
        $limitSql = "SELECT * FROM (SELECT ORC_TMP1.*, ROWNUM ORC_RN FROM ({$sql}) ORC_TMP1 WHERE ROWNUM <= {$end}) ORC_TMP2 WHERE ORC_TMP2.ORC_RN >= {$limit[0]}";
        return $limitSql;
    }

    /**
     * order分析
     *
     * @access protected
     * @param mixed $order            
     * @return string
     */
    protected function parseOrder($order)
    {
        return ! empty($order) ? ' ORDER BY ' . $order : '';
    }

    /**
     * group分析
     *
     * @access protected
     * @param mixed $group            
     * @return string
     */
    protected function parseGroup($group)
    {
        return ! empty($group) ? ' GROUP BY ' . $group : '';
    }

    /**
     * having分析
     *
     * @access protected
     * @param string $having            
     * @return string
     */
    protected function parseHaving($having)
    {
        return ! empty($having) ? ' HAVING ' . $having : '';
    }

    /**
     * distinct分析
     *
     * @access protected
     * @param mixed $distinct            
     * @return string
     */
    protected function parseDistinct($distinct)
    {
        return ! empty($distinct) ? ' DISTINCT ' : '';
    }

    /**
     * 替换SQL语句中表达式
     *
     * @access public
     * @param array $options
     *            表达式
     * @return string
     */
    public function parseSql($sql, $options = [])
    {
        $sql = str_replace([
            '%TABLE%',
            '%DISTINCT%',
            '%FIELD%',
            '%WHERE%',
            '%GROUP%',
            '%HAVING%',
            '%ORDER%',
        ], [
            $this->parseTable($options['table']),
            $this->parseDistinct(isset($options['distinct']) ? $options['distinct'] : ''),
            $this->parseField(! empty($options['field']) ? $options['field'] : '*'),
            $this->parseWhere(! empty($options['where']) ? $options['where'] : ''),
            $this->parseGroup(! empty($options['group']) ? $options['group'] : ''),
            $this->parseHaving(! empty($options['having']) ? $options['having'] : ''),
            $this->parseOrder(! empty($options['order']) ? $options['order'] : ''),
        ], $sql);
        if (! empty($options['limit'])){
            $sql = $this->parseLimit($sql, $options['limit']);
        }
        return $sql;
    }

    /**
     * SQL指令安全过滤
     *
     * @access public
     * @param string $str
     *            SQL字符串
     * @return string
     */
    public function escapeString($str)
    {
        return addslashes($str);
    }

    function insert($data, $options, $replace = false)
    {
        $fields = $values = [];
        foreach ($data as $key => $val) {
            if (is_array($val) && 'exp' == $val[0]) {
                $fields[] = $this->parseKey($key);
                $values[] = $val[1];
            } else { 
                $fields[] = $this->parseKey($key);
                $values[] = $this->parseValue($val);
            }
        }
        $sql = ($replace ? 'REPLACE' : 'INSERT') . ' INTO ' . $this->parseTable($options['table']) . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
        return $this->execute($sql, ! empty($options['fetch_sql']) ? true : false);
    }

    function delete($options)
    {
        if (empty($options['where'])) {
            return false;
        }
        $table = $this->parseTable($options['table']);
        $where = $this->parseWhere($options['where']);
        $sql = "DELETE FROM {$table} {$where}";
        return $this->execute($sql, ! empty($options['fetch_sql']));
    }

    function update($data, $options)
    {
        if (empty($options['where'])) {
            return false;
        }
        $table = $this->parseTable($options['table']);
        $where = $this->parseWhere($options['where']);
        $set = $this->parseSet($data);
        $sql = "UPDATE {$table} {$set} {$where}";
        return $this->execute($sql, ! empty($options['fetch_sql']));
    }

    function select($options)
    {
        $sql = $this->parseSql($this->selectSql, $options);
        $result = $this->query($sql, ! empty($options['fetch_sql']));
        return $result;
    }

    function execute($sql, $fetch = false)
    {
        $this->sql = $sql;
        if ($fetch) {
            return $this->sql;
        }
        $stid = oci_parse($this->conn, $this->sql);
        $result = oci_execute($stid,OCI_COMMIT_ON_SUCCESS);
        oci_free_statement($stid);
        return $result;
    }

    function query($sql, $fetch = false)
    {
        $this->sql = $sql;
        if ($fetch) {
            return $this->sql;
        }
        $stid = oci_parse($this->conn, $this->sql);
        oci_execute($stid);
        $result = [];
        while ($row = oci_fetch_assoc ($stid)){
            unset($row['ORC_RN']);
            $newRow = [];
            foreach ($row as $k=>$v){
                $newRow[strtolower($k)] = $v;
            }
            $result[] = $newRow;
        }
        return $result;
    }

    /**
     * 启动事务
     * 
     * @access public
     * @return void
     */
    public function startTrans()
    {
        if ($this->transTimes == 0) {
            $this->conn->beginTransaction();
        }
        $this->transTimes ++;
        return;
    }

    /**
     * 用于非自动提交状态下面的查询提交
     * 
     * @access public
     * @return boolean
     */
    public function commit()
    {
        if ($this->transTimes > 0) {
            $result = $this->conn->commit();
            $this->transTimes = 0;
            if (! $result) {
                // TODO 错误处理
                return false;
            }
        }
        return true;
    }

    /**
     * 事务回滚
     * 
     * @access public
     * @return boolean
     */
    public function rollback()
    {
        if ($this->transTimes > 0) {
            $result = $this->conn->rollback();
            $this->transTimes = 0;
            if (! $result) {
                // TODO 错误处理
                return false;
            }
        }
        return true;
    }
}