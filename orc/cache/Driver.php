<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc redis缓存驱动
 */
namespace cache;

abstract class Driver
{
    use \traits\Instance;

    protected $handler = null;

    abstract function set($key, $value, $expire = null);

    abstract function get($key);

    public function __call($functionName, $args)
    {
        return call_user_func_array([
            $this->handler,
            $functionName
        ], $args);
    }
}