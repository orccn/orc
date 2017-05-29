<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-09-05
 * @desc 依赖处理类
 */
namespace library;

class Dependency
{

    /**
     * $config = [
     * 'lt'=>['int','abc'],
     * 'int'=>['long_int','ff'],
     * 'long_int'=>['ss','ff'],
     * 'a'=>['b','e'],
     * 'b'=>['c','d']
     * ];
     */
    protected $config = [];

    private $expandConfig = [];

    public function __construct($config = [])
    {
        $this->set($config);
    }

    public function set($key, $values = '')
    {
        if (empty($values)) {
            if (! is_array($key))
                return;
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            if (! is_array($values))
                $values = [
                    $values
                ];
            if (isset($this->config[$key])) {
                $values = array_merge($this->config[$key], $values);
            }
            $this->config[$key] = array_unique($values);
        }
    }

    public function get($key = '')
    {
        if (! $key) {
            return $this->config;
        } else {
            return isset($this->config[$key]) ? $this->config[$key] : [];
        }
    }

    public function expand($key = '')
    {
        $arr = [];
        if (! $key) {
            foreach ($this->config as $key => $values) {
                $arr[$key] = $this->expand($key);
            }
            return $arr;
        }
        
        if (! isset($this->config[$key])) {
            return $arr;
        }
        if (isset($this->expandConfig[$key])) {
            return $this->expandConfig[$key];
        }
        $values = $this->config[$key];
        foreach ($values as $value) {
            if (isset($this->expandConfig[$value])) {
                $arr = array_unique(array_merge($this->expandConfig[$value], $arr));
            } elseif (isset($this->config[$value])) {
                $arr = array_unique(array_merge($this->expand($value), $arr));
            }
            $arr[] = $value;
        }
        $this->expandConfig[$key] = $arr;
        return $arr;
    }
}