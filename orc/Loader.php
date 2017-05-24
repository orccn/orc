<?php
/**
 * @author lpywy728394@163.com
 * @date 2017-05-21
 * @desc 
 */
namespace orc;

class Loader
{

    private static $paths = [];

    public static function rigist($path = null)
    {
        if ($path) {
            self::addPath($path);
        }
        $vendorAutoFile = '../vendor/autoload.php';
        if (is_file($vendorAutoFile)) {
            require $vendorAutoFile;
        }
        spl_autoload_register('orc\Loader::autoload');
    }

    /**
     * 添加配置目录
     *
     * @param string $path
     *            配置路径
     */
    public static function addPath($path)
    {
        if (is_array($path)) {
            self::$paths = array_merge(self::$paths, array_map('fmtdir', $path));
        } else {
            self::$paths[] = fmtdir($path);
        }
    }

    /**
     * 自动加载方法
     *
     * @param string $class            
     */
    public static function autoload($class)
    {
        foreach (self::$paths as $filePath) {
            $filePath .= str_replace('\\', '/', $class) . ".php";
            if (file_exists($filePath)) {
                require_once $filePath;
            }
        }
    }
}





