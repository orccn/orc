<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc redis缓存驱动
 */
namespace orc\database;

abstract class Driver
{

    protected $conn = null;

    abstract function connect();

    public function __call($functionName, $args)
    {
        return call_user_func_array([
            $this->conn,
            $functionName
        ], $args);
    }
}