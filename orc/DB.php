<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 数据库管理类
 */
namespace orc;

class DB
{

    private static $instances = [];

    private static $default = [
        'key' => 'default',
        'charset' => 'utf8',
        'type' => 'mysql'
    ];

    /**
     * @param string $key            
     * @return \database\Mysql
     */
    public static function getInstance($key = '')
    {
        if (! $key) {
            $key = self::$default['key'];
        }
        if (! isset(self::$instances[$key])) {
            $config = self::getConfigItem($key);
            $class = "orc\\database\\" . ucfirst($config['type']);
            if (class_exists($class)) {
                self::$instances[$key] = new $class($config);
            } else {
                E("class {$class} not exists");
            }
        }
        return self::$instances[$key];
    }

    /**
     * 获取数据库具体配置信息
     *
     * @param unknown $ms            
     */
    private static function getConfigItem($key)
    {
        $config = config("database.{$key}");
        if (! $config) {
            E("database.{$key} not exists");
        }
        $config['charset'] = empty($config['charset']) ? self::$default['charset'] : strtolower($config['charset']);
        $config['type'] = empty($config['type']) ? self::$default['type'] : strtolower($config['type']);
        return $config;
    }
}
