<?php
/**
 * @author lpywy728394@163.com
 * @date 2017-05-21
 * @desc 创建对象
 */
namespace orc\traits;

trait Instance
{

    /**
     *
     * @return static
     */
    public static function instance()
    {
        return (new \ReflectionClass(get_called_class()))->newInstanceArgs(func_get_args());
    }

    /**
     *
     * @return static
     */
    public static function single()
    {
        static $arr = array();
        $className = get_called_class();
        if (! isset($arr[$className])) {
            $arr[$className] = call_user_func_array('self::instance', func_get_args());
        }
        return $arr[$className];
    }
}