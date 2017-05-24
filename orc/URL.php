<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc URL类：URL解析、重写、过滤
 */
namespace orc;

class URL
{

    public static function getCurrentURL()
    {
        static $url = null;
        if ($url) {
            return $url;
        }
        $url = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ? 'https://' : 'http://';
        $url .= $_SERVER["SERVER_NAME"];
        if ($_SERVER["SERVER_PORT"] != "80") {
            $url .= ":{$_SERVER["SERVER_PORT"]}";
        }
        $url .= $_SERVER["REQUEST_URI"];
        return $url;
    }

    public static function getHost($url = '')
    {
        $host = '';
        if ($url === '') {
            $url = self::getCurrentURL();
        }
        $url = parse_url($url);
        $host = $url['host'];
        if (isset($url['port'])) {
            $host .= ":" . $url['port'];
        }
        return $host;
    }

    public static function setURLQuery(array $params, $url = '')
    {
        $str = '';
        if ($url === '') {
            $url = self::getCurrentURL();
        }
        $rs = parse_url($url);
        if (isset($rs['scheme'])) {
            $str .= $rs['scheme'] . "//";
        }
        $str .= self::getHost($url);
        if (isset($rs['path'])) {
            $str .= $rs['path'];
        }
        $query = array();
        if (isset($rs['query']) && $rs['query']) {
            parse_str($rs['query'], $query);
        }
        $str .= "?" . http_build_query(array_merge($query, $params));
        if (isset($rs['fragment'])) {
            $str .= "#" . $rs['fragment'];
        }
        return $str;
    }

    public static function getRewriteURL()
    {}

    /**
     * 设置url重写规则
     *
     * @param unknown $match
     *            匹配规则
     * @param unknown $target
     *            目标url
     */
    public function setRewriteRule($match, $target)
    {}

    /**
     * url重写
     */
    private function rewrite()
    {}

    /**
     * url过滤
     */
    private function filter()
    {}
}