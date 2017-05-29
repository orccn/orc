<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc redis缓存驱动
 */
namespace cache;

class Redis extends Driver
{

    public function __construct($config)
    {
        if (isset($config[0]) && is_array($config[0])) {
            $config = $config[array_rand($config)];
        }
        $this->handler = new \Redis();
        $rs = $this->handler->connect($config['host'], $config['port']);
    }

    public function set($key, $value, $expire = null)
    {
        return $this->handler->set($key, serialize($value), $expire);
    }

    public function get($key)
    {
        $value = $this->handler->get($key);
        if ($value) {
            $value = unserialize($value);
        }
        return $value;
    }
}