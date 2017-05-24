<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 响应封装
 */
namespace orc;

class Response
{

    public static function setcookie($name, $value, $expire = null, $path = '/', $domain = null, $secure = null, $httponly = true)
    {
        if ($expire) {
            $expire = time() + $expire;
        }
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public static function redirect($url, $delay = 0)
    {
        if (! headers_sent()) {
            if ($delay == 0) {
                header('Location: ' . $url);
            } else {
                header("refresh:{$delay};url={$url}");
            }
            exit();
        } else {
            $str = "<meta http-equiv='Refresh' content='{$delay};URL={$url}'>";
            exit($str);
        }
    }

    public static function output($content)
    {
        if ($content !== null) {
            echo $content;
        }
    }

    public static function outputJson($data, $jsonp = false)
    {
        header('Content-Type:application/json; charset=utf-8');
        $json = json_encode($data);
        if ($jsonp) {
            $json = $jsonp . '(' . $json . ');';
        }
        self::output($json);
    }

    public static function exitJson($data, $jsonp = '')
    {
        self::outputJson($data);
        exit();
    }
    
    public static function show404()
    {
        echo "this page is not found.";
        // header('HTTP/1.1 404 Not Found');
        exit();
    }
    
}