<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 国际化
 */
namespace orc;

class Lang
{

    private static $config = [];

    private static $path = null;

    private static $lang = 'zh-cn';

    private static $defaultFile = 'main';

    /**
     * 设置配置目录
     *
     * @param string $path
     *            配置路径
     */
    public static function addPath($path)
    {
        self::$path = fmtdir($path);
    }

    /**
     * 获取配置信息
     *
     * @param string $key
     *            要获取的配置的键
     * @param mixed $params
     *            可能存在的文本参数
     * @return string
     */
    public static function get($key = '', $params = null)
    {
        if ($params !== null) {
            $params = (array) $params;
        }
        $key = explode('.', $key, 2);
        if (! isset($key[1])) {
            array_unshift($key, self::$defaultFile);
        }
        $data = self::load($key[0]);
        if (! empty($key[1])) {
            $data = isset($data[$key[1]]) ? $data[$key[1]] : null;
        }
        if (is_string($data) && $params) {
            array_unshift($params, $data);
            $rs = trim(call_user_func_array('sprintf', $params));
        }
        return $data;
    }

    /**
     * 设置语言
     *
     * @param string $lang            
     */
    public static function setLang($lang)
    {
        self::$lang = $lang;
    }

    /**
     * 获取配置数据
     *
     * @param string $filename            
     * @return mixed
     */
    public static function load($filename)
    {
        $file = self::$path . self::$lang . '/' . $filename . ".php";
        if (! isset(self::$config[$filename])) {
            self::$config[$filename] = parse_file($file);
        }
        return self::$config[$filename];
    }
}
