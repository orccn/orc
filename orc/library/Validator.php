<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 验证类
 */
namespace orc\library;

class Validator
{
    use \orc\traits\Instance;
    
    protected $name = '';
    
    protected $data = null;

    protected $rules = [];
    
    protected $isRun = false;
    
    protected $error = [
        'required'      => '%s不能为空',
        'english'       => '%s必须为英文字母或者下划线',
        'englishArabic' => '%s必须为英文字母、数字或者下划线',
        'integer'       => '%s必须为整数',
        'numeric'       => '%s必须为整数或者小数',
        'natural'       => '%s必须为自然数',
        'naturalNoZero' => '%s必须为大于零的自然数',
        'maxLen'        => '%s的长度不能大于%d个字符',
        'minLen'        => '%s的长度不能小于%d个字符',
        'lt'            => '%s必须小于%s',
        'gt'            => '%s必须大于%s',
        'lte'           => '%s不能大于%s',
        'gte'           => '%s不能小于%s',
    ];
    
    public function __construct($data, $name='', $rules = [])
    {
        $this->name = $name;
        $this->data = $data;
        $this->rules = $this->formatRules($rules);
    }
    
    /**
     * 运行验证规则
     * @return boolean
     */
    public function run()
    {
        $this->isRun = true;
        $rules = $this->rules;
        // 非必填项并且数据为空
        if (array_key_exists('notRequired', $rules)) {
            if (empty($this->data)){
                return null;
            }else{
                unset($rules['notRequired']);
            }
        }
        foreach ($rules as $method => $args) {
            $rs = call_user_func_array([$this,$method], $args);
            if ($rs === false) {
                return $method;
            }
        }
        return null;
    }
    
    /**
     * 动态添加验证方法
     * 
     * $validator = new Validator(12,'aaa');
        $validator->addValidator('dayu', function ($self,$num){
           return  $self->getData()>$num;
        },'%s必须大于%s');
        $error = $validator->dayu(1111)->getError();
     * 
     * @param unknown $methodName
     * @param unknown $methodBody
     * @param string $errorMsg
     */
    public function addValidator($methodName,$methodBody,$errorMsg='')
    {
        $this->extValidator[$methodName] = $methodBody;
        $this->setError($methodName,$errorMsg);
    }

    /**
     * 设置错误信息
     * @param unknown $rule
     * @param string $errorMsg
     */
    public function setError($rule, $errorMsg = '')
    {
        if ($errorMsg) {
            $this->error[$rule] = $errorMsg;
        } else {
            if (is_array($rule)) {
                $this->error = array_merge($this->error, $rule);
            }
        }
    }
    
    /**
     * 获取错误信息
     */
    public function getError()
    {
        $invalidRule = $this->run();
        if (!$invalidRule){
            return $invalidRule;
        }
        if (!isset($this->error[$invalidRule])){
            return '未知错误';
        }
        $params = array_merge([$this->error[$invalidRule],$this->name],$this->rules[$invalidRule]);
        $errorMsg = call_user_func_array('sprintf', $params);
        return $errorMsg;
    }
    
    /**
     * 格式化规则
     * @param array $rules
     */
    protected function formatRules($rules)
    {
        if (! is_array($rules)) {
            $rules = array($rules);
        }
        $kvRules = [];
        foreach ($rules as $k=>$v){
            list($method,$args) = is_int($k) ? [$v,[]] : [$k,$v];
            if (is_string($method) && ! method_exists($this, $method)) {
                E("validate rule['{$method}'] not exists");
            }
            if (! is_array($args)) {
                $args = [$args];
            }
            $kvRules[$method] = $args;
        }
        // 不是非必填选项则必定是必填选项
        if (! array_key_exists('notRequired', $kvRules)) {
            $kvRules = array_merge(['required' => []],$kvRules);
        }
        return $kvRules;
    }
    
    /**
     * @param unknown $method
     * @param unknown $args
     * @param unknown $callback
     * @return mixed|\Util\Validator
     */
    protected function routine($method, $args, $callback)
    {
        if ($this->isRun) {
            return call_user_func_array($callback, $args);
        }
        $this->rules[$method] = $args;
        return $this;
    }

    public function required()
    {
        return $this->routine(__FUNCTION__, func_get_args(), function () {
            return ! empty($this->data);
        });
    }
    /**
     * 字段为可选的时候，在run中有特殊处理
     */
    public function notRequired()
    {
        return $this->routine(__FUNCTION__, func_get_args(), function () {
            return true;
        });
    }

    public function english()
    {
        return $this->routine(__FUNCTION__, func_get_args(), function () {
            return (bool) preg_match("/^([_a-zA-Z])+$/i", $this->data);
        });
    }
    
    public function englishArabic()
    {
        return $this->routine(__FUNCTION__, func_get_args(), function () {
            return (bool) preg_match("/^([_a-zA-Z0-9])+$/i", $this->data);
        });
    }

    public function integer()
    {
        return $this->routine(__FUNCTION__, func_get_args(), function () {
            return (bool) preg_match('/^[\-+]?[0-9]+$/', $this->data);
        });
    }
    
    public function numeric()
    {
        return $this->routine(__FUNCTION__, func_get_args(), function () {
            return is_numeric($this->data);
        });
    }

    public function natural()
    {
        return $this->routine(__FUNCTION__, func_get_args(), function () {
            return (bool) preg_match('/^[0-9]+$/', $this->data);
        });
    }

    public function naturalNoZero()
    {
        return $this->routine(__FUNCTION__, func_get_args(), function () {
            $this->natural();
            return $this->data != 0;
        });
    }

    public function maxLen($len)
    {
        return $this->routine(__FUNCTION__, func_get_args(), function ($len) {
            return mb_strlen($this->data, 'utf8') <= $len;
        });
    }

    public function minLen($len)
    {
        return $this->routine(__FUNCTION__, func_get_args(), function ($len) {
            return mb_strlen($this->data, 'utf8') >= $len;
        });
    }
    
    public function lt($num)
    {
        return $this->routine(__FUNCTION__, func_get_args(), function ($num) {
            $this->numeric();
            return $this->data < $num;
        });
    }
    
    public function gt($num)
    {
        return $this->routine(__FUNCTION__, func_get_args(), function ($num) {
            $this->numeric();
            return $this->data > $num;
        });
    }
    
    public function lte($num)
    {
        return $this->routine(__FUNCTION__, func_get_args(), function ($num) {
            $this->numeric();
            return $this->data <= $num;
        });
    }
    
    public function gte($num)
    {
        return $this->routine(__FUNCTION__, func_get_args(), function ($num) {
            $this->numeric();
            return $this->data >= $num;
        });
    }
}