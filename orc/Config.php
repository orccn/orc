<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 配置管理类
 */
namespace orc;

class Config
{

    private static $config = [];

    private static $paths = [];

    private static $defaultFile = 'main';

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
     * 获取配置信息
     *
     * @param string $key
     *            要获取的配置的文件名和键
     * @param mixed $default
     *            当未获取到配置时的默认值
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $key = explode('.', $key, 2);
        if (! isset($key[1])) {
            array_unshift($key, self::$defaultFile);
        }
        $data = self::load($key[0]);
        if (! empty($key[1])) {
            $data = isset($data[$key[1]]) ? $data[$key[1]] : null;
        }
        return $data ? $data : $default;
    }

    /**
     * 获取配置数据
     *
     * @param string $filename            
     * @return mixed
     */
    public static function load($filename)
    {
        if (! isset(self::$config[$filename])) {
            foreach (self::$paths as $path) {
                $data = parse_file($path . $filename . ".php");
                if ($data !== null) {
                    if (! isset(self::$config[$filename])) {
                        self::$config[$filename] = [];
                    }
                    self::$config[$filename] = array_cover_recursive(self::$config[$filename], $data);
                }
            }
            if (! isset(self::$config[$filename])) {
                self::$config[$filename] = null;
            }
        }
        return self::$config[$filename];
    }
}
