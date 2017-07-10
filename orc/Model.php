<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 模型基类
 */
namespace orc;

class Model
{
    use traits\Instance;

    protected $dbKey = 'default';

    protected $tableName = '';

    protected $options = [];

    protected $pk = 'id';

    protected $db = null;

    protected $pageSize = 20;

    public function __construct($tableName = '', $dbKey = '')
    {
        if ($tableName) {
            $this->tableName = $tableName;
        }
        if ($dbKey) {
            $this->dbKey = $dbKey;
        }
    }

    protected function db()
    {
        if (! $this->db) {
            $this->db = db($this->dbKey);
        }
        return $this->db;
    }

    public function insert($data, $replace = false)
    {
        $options = $this->getOptions();
        $rs = $this->db()->insert(null2empty($data), $options, $replace);
        return $rs;
    }

    public function delete($where)
    {
        if (is_numeric($where) || is_string($where)) {
            $this->where([
                $this->getPk() => $where
            ]);
        }
        $rs = $this->db()->delete($this->getOptions());
        return $rs;
    }

    public function update($data, $where = '')
    {
        if ($where) {
            if (is_array($where)) {
                $this->where($where);
            } else {
                $this->where([
                    $this->getPk() => $where
                ]);
            }
        }
        $options = $this->getOptions();
        $rs = $this->db()->update(null2empty($data), $options);
        return $rs;
    }

    public function select($key = '')
    {
        if (isset($this->options['pageParams']) && ($pageParams = $this->options['pageParams'])) {
            unset($this->options['pageParams']);
            $obj = clone $this;
            $pageInfo = $this->pageInfo($obj->count(), $pageParams[0], $pageParams[1]);
            $this->limit("{$pageInfo['offset']},{$pageInfo['pageSize']}");
        }
        $options = $this->getOptions();
        $arr = $this->db()->select($options);
        if (is_array($arr) && $key) {
            $arr = array_column($arr, null, $key);
        }
        return $arr;
    }

    public function getRow($id = null)
    {
        $where = $id ? [
            $this->getPk() => $id
        ] : $this->options['where'];
        $result = $this->where($where)
            ->limit(1)
            ->select();
        if (is_string($result)) {
            return $result;
        } else {
            return isset($result[0]) ? $result[0] : [];
        }
    }

    public function count()
    {
        $row = $this->fields('count(1) num')->select();
        return intval($row[0]['num']);
    }

    public function getField($valueField, $keyField = '')
    {
        $fields = $keyField ? $keyField . ',' . $valueField : $valueField;
        $arr = $this->fields($fields)->select();
        return array_column($arr, $valueField, $keyField);
    }

    public function group($group)
    {
        $this->options['group'] = $group;
        return $this;
    }

    public function having($having)
    {
        $this->options['having'] = $having;
        return $this;
    }

    /**
     * 排序
     *
     * @param string $order            
     * @return \orc\Model
     */
    public function order($order)
    {
        $this->options['order'] = $order;
        return $this;
    }

    /**
     * limit 查询
     *
     * @param string $limit            
     * @return \orc\Model
     */
    public function limit($limit)
    {
        $this->options['limit'] = $limit;
        return $this;
    }

    /**
     * SQL查询
     *
     * @access public
     * @param string $sql
     *            SQL指令
     * @param mixed $parse
     *            是否需要解析SQL
     * @return mixed
     */
    public function query($sql, $parse = false)
    {
        if ($parse === true) {
            $sql = $this->parseSql($sql, $parse);
        }
        return $this->db()->query($sql);
    }

    /**
     * 执行SQL语句
     *
     * @access public
     * @param string $sql
     *            SQL指令
     * @param mixed $parse
     *            是否需要解析SQL
     * @return false | integer
     */
    public function execute($sql, $parse = false)
    {
        if ($parse === true) {
            $sql = $this->parseSql($sql, $parse);
        }
        return $this->db()->execute($sql);
    }

    /**
     * 解析SQL语句
     *
     * @access public
     * @param string $sql
     *            SQL指令
     * @param boolean $parse
     *            是否需要解析SQL
     * @return string
     */
    protected function parseSql($sql)
    {
        $options = $this->getOptions();
        $sql = $this->db()->parseSql($sql, $options);
        return $sql;
    }

    public function where($where)
    {
        if (isset($this->options['where'])) {
            $this->options['where'] = array_merge($this->options['where'], $where);
        } else {
            $this->options['where'] = $where;
        }
        return $this;
    }

    public function useDB($dbKey)
    {
        $this->dbKey = $dbKey;
    }

    public function table($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function fields($fields)
    {
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        $this->options['field'] = $fields;
        return $this;
    }

    public function distinct()
    {
        $this->options['distinct'] = true;
        return $this;
    }

    /**
     * 获取主键名称
     *
     * @access public
     * @return string
     */
    public function getPk()
    {
        return $this->pk;
    }

    /**
     * 获取执行的SQL语句
     *
     * @access public
     * @param boolean $fetch
     *            是否返回sql
     * @return Model
     */
    public function fetchSql($fetch = true)
    {
        $this->options['fetch_sql'] = $fetch;
        return $this;
    }

    /**
     * 参数绑定
     *
     * @access public
     * @param string $key
     *            参数名
     * @param mixed $value
     *            绑定的变量及绑定参数
     * @return Model
     */
    public function bind($key, $value = false)
    {
        if (is_array($key)) {
            $this->options['bind'] = $key;
        } else {
            $this->options['bind'][$key] = $value;
        }
        return $this;
    }

    protected function getOptions($options = [])
    {
        if (is_array($options)) {
            $options = array_merge($this->options, $options);
        }
        if (! isset($options['table'])) {
            $options['table'] = $this->tableName;
        }
        $this->options = [];
        return $options;
    }

    /**
     * 启动事务
     *
     * @access public
     * @return void
     */
    public function startTrans()
    {
        $this->commit();
        $this->db()->startTrans();
        return;
    }

    /**
     * 提交事务
     *
     * @access public
     * @return boolean
     */
    public function commit()
    {
        return $this->db()->commit();
    }

    /**
     * 事务回滚
     *
     * @access public
     * @return boolean
     */
    public function rollback()
    {
        return $this->db()->rollback();
    }

    /**
     * 设置分页查询
     *
     * @param number $page            
     * @param number $pageSize            
     * @return model_base
     */
    public function page($page, $pageSize = null)
    {
        if (! $pageSize) {
            $pageSize = $this->pageSize;
        }
        $this->options['pageParams'] = [
            $page,
            $pageSize
        ];
        return $this;
    }

    /**
     * 分页计算
     *
     * @param munber $count            
     * @param number $page            
     * @param number $pageSize            
     * @return array
     */
    public function pageInfo($count, $page = 1, $pageSize = null)
    {
        if (! $pageSize) {
            $pageSize = $this->pageSize;
        }
        $page = max(1, intval($page));
        $pageSize = max(1, min(100, $pageSize));
        
        $totalPage = ceil($count / $pageSize) + 1;
        if ($page > $totalPage) {
            $page = $totalPage;
        }
        $offset = ($page - 1) * $pageSize;
        return $this->options['pageInfo'] = [
            'page' => $page,
            'totalPage' => $totalPage,
            'offset' => $offset,
            'pageSize' => $pageSize,
            'totalItems' => $count
        ];
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * 获取当前分页结果
     *
     * @return array
     */
    public function getCurrentPageInfo()
    {
        return $this->options['pageInfo'];
    }
}