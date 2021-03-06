<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 公用方法
 */
use orc\Config;
use orc\Lang;
use orc\DI;

function I($name, $all = false)
{
    if (strpos($name, '.')) {
        list ($method, $name) = explode('.', $name, 2);
    } else {
        $method = 'request';
    }
    
    switch (strtolower($method)) {
        case 'get':
            $input = & $_GET;
            break;
        case 'post':
            $input = & $_POST;
            break;
        case 'put':
            parse_str(file_get_contents('php://input'), $input);
            break;
        case 'request':
            $input = & $_REQUEST;
            break;
        case 'session':
            $input = & $_SESSION;
            break;
        case 'cookie':
            $input = & $_COOKIE;
            break;
        case 'server':
            $input = & $_SERVER;
            break;
        case 'globals':
            $input = & $GLOBALS;
            break;
        default:
            return '';
    }
    
    $filter = config('input_filter') ? config('input_filter') : [
        'trim',
        'htmlspecialchars'
    ];
    if ($all) {
        return array_map_recursive($filter, $input);
    }
    if (! isset($input[$name])) {
        return '';
    }
    return array_map_recursive($filter, $input[$name]);
}

function di($alias = null)
{
    if (is_null($alias)) {
        return DI::ins();
    } else {
        return di()->get($alias, array_slice(func_get_args(), 1));
    }
}

/**
 * 抛出异常处理
 *
 * @param string $msg
 *            异常消息
 * @param integer $code
 *            异常代码 默认为0
 * @throws Core\Exception
 * @return void
 */
function E($msg = '', $code = 0)
{
    if (is_object($msg)) {
        throw $msg;
    } else {
        throw new \orc\Exception($msg, $code);
    }
}

function pre($var)
{
    echo '<pre>';
    print_r($var);
    exit();
}

function config($key, $default = null)
{
    return di('config')->get($key, $default);
}

function lang($key = '', $params = null)
{
    return di('lang')->get($key, $params);
}

function gvar($key, $value = null)
{
    static $vars = [];
    if ($value !== null) {
        $vars[$key] = $value;
    } else {
        return isset($vars[$key]) ? $vars[$key] : null;
    }
}

function cache($key)
{
    static $instances = [];
    if (! isset($instances[$key])) {
        $config = config("cache.{$key}");
        if (! $config) {
            E("cache.{$key} not exists");
        }
        $class = "orc\\cache\\" . ucfirst($config['type']);
        if (class_exists($class)) {
            $instances[$key] = new $class($config['servers']);
        } else {
            E("class {$class} not exists");
        }
    }
    return $instances[$key];
}

function db($key = 'default')
{
    static $instances = [];
    if (! isset($instances[$key])) {
        $config = config("database.{$key}");
        if (! $config) {
            E("database.{$key} not exists");
        }
        $config['charset'] = empty($config['charset']) ? 'utf8' : strtolower($config['charset']);
        $config['type'] = empty($config['type']) ? 'mysql' : strtolower($config['type']);
        $class = "orc\\database\\" . ucfirst($config['type']);
        $instances[$key] = new $class($config);
    }
    return $instances[$key];
}

function null2empty($data)
{
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $data[$k] = null2empty($v);
        }
        return $data;
    } else {
        return $data === null ? '' : $data;
    }
}

function array_map_recursive($callback, $data)
{
    if (is_array($data)) {
        $result = [];
        foreach ($data as $key => $val) {
            $result[$key] = array_map_recursive($callback, $val);
        }
        return $result;
    } else {
        foreach ($callback as $cb) {
            $data = is_array($data) ? array_map_recursive($cb, $data) : call_user_func($cb, $data);
        }
        return $data;
    }
}

function array_cover_recursive($arr1, $arr2)
{
    $union = $arr1;
    foreach (func_get_args() as $arr) {
        if (! is_array($arr)) {
            continue;
        }
        foreach ($arr as $key => $val) {
            if (! isset($union[$key])) {
                $union[$key] = [];
            }
            $union[$key] = is_array($val) ? array_cover_recursive($union[$key], $val) : $val;
        }
    }
    return $union;
}

function dir_map($dir, $depth = 0)
{
    if ($fp = @opendir($dir)) {
        $files = [];
        $dir = fmtdir($dir);
        
        while (false !== ($file = readdir($fp))) {
            if ($file === '.' or $file === '..') {
                continue;
            }
            
            if (($depth > 0) && is_dir($dir . $file)) {
                $files[$file] = dir_map($dir . $file, $depth - 1);
            } else {
                $files[$file] = $dir . $file;
            }
        }
        closedir($fp);
        return $files;
    }
    return false;
}

function fmtdir($dir)
{
    return rtrim($dir, '/') . '/';
}

/**
 * 加载配置文件 支持格式转换 仅支持一级配置
 *
 * @param string $file
 *            配置文件名
 * @param string $parse
 *            配置解析方法 有些格式需要用户自己解析
 * @return array
 */
function parse_file($file, $parse = '')
{
    if (! is_file($file)) {
        return null;
    }
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'php':
            return include $file;
        case 'ini':
            return parse_ini_file($file);
        case 'yaml':
            return yaml_parse_file($file);
        case 'xml':
            return (array) simplexml_load_file($file);
        case 'json':
            return json_decode(file_get_contents($file), true);
        default:
            if (function_exists($parse)) {
                return $parse($file);
            } else {
                return null;
            }
    }
}

/**
 * 驼峰转换为小写字母加下划线
 *
 * @param string $str            
 * @return string
 */
function camel2underline($str)
{
    return strtolower(preg_replace('/(?<=[a-zA-Z])(?=[A-Z])/', '_', $str));
}

/**
 * 小写字母加下划线转换为驼峰
 *
 * @param string $str            
 * @param boolean $changeFirst
 *            首字母是否大写
 * @return string
 */
function underline2camel($str, $changeFirst = true)
{
    $pattern = $changeFirst ? '/(^|_)([a-z])/' : '/(_)([a-z])/';
    return preg_replace_callback($pattern, function ($m) {
        return strtoupper($m[2]);
    }, $str);
}

