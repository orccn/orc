<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 钩子类
 */
namespace orc;

class Hook
{

    private static $tags = [];

    /**
     * 动态添加插件到某个标签
     *
     * @param string $tag
     *            标签名称
     * @param mixed $name
     *            插件名称
     * @return void
     */
    public static function set($tag, $name)
    {
        if (! isset(self::$tags[$tag])) {
            self::$tags[$tag] = [];
        }
        if (is_array($name)) {
            self::$tags[$tag] = array_merge(self::$tags[$tag], $name);
        } else {
            self::$tags[$tag][] = $name;
        }
    }

    /**
     * 获取插件信息
     *
     * @param string $tag
     *            插件位置 留空获取全部
     * @return array
     */
    public static function get($tag = '')
    {
        if (empty($tag)) {
            return self::$tags;
        } else {
            return self::$tags[$tag];
        }
    }

    /**
     * 触发标签的插件
     *
     * @param string $tag
     *            标签名称
     * @param mixed $params
     *            传入参数
     * @return void
     */
    public static function trigger($tag, &$params = null)
    {
        if (! isset(self::$tags[$tag])){
            return null;
        }
        foreach (self::$tags[$tag] as $name) {
            self::exec($name, $tag, $params);
        }
    }

    /**
     * 执行某个插件
     *
     * @param string $name
     *            插件名称
     * @param string $tag
     *            方法名（标签名）
     * @param Mixed $params
     *            传入的参数
     * @return void
     */
    static public function exec($name, $tag, &$params = null)
    {
        $plugin = new $name();
        if (!method_exists($plugin, $tag)){
            E("Hook plugin[{$plugin}] method[{$tag}] not exists!");
        }
        return $plugin->$tag($params);
    }
}