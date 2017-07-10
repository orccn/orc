<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 响应封装
 */
namespace orc;

class Response
{

    public function setcookie($name, $value, $expire = null, $path = '/', $domain = null, $secure = null, $httponly = true)
    {
        if ($expire) {
            $expire = time() + $expire;
        }
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function redirect($url, $delay = 0)
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

    public function output($content)
    {
        if ($content !== null) {
            echo $content;
        }
    }

    public function outputJson($data, $jsonp = false)
    {
        header('Content-Type:application/json; charset=utf-8');
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);
        if ($jsonp) {
            $json = $jsonp . '(' . $json . ');';
        }
        $this->output($json);
    }

    public function exitJson($data, $jsonp = '')
    {
        $this->outputJson($data);
        exit();
    }

    public function error($msg, $code = 1)
    {
        $data = [
            'code' => $code,
            'msg' => $msg
        ];
        $this->exitJson($data);
    }

    public function success($data = null, $more = null)
    {
        $msg = is_string($more) ? $more : 'ok';
        $data = [
            'code' => 0,
            'msg' => $msg,
            'data' => $data
        ];
        if (is_array($more)) {
            $data = array_merge($data, $more);
        }
        $this->exitJson($data);
    }

    public function show404()
    {
        echo "this page is not found.";
        // header('HTTP/1.1 404 Not Found');
        exit();
    }
}