<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 钩子类
 */
namespace orc;

class Hook
{

    protected $tags = [];

    /**
     * 动态添加插件到某个标签
     *
     * @param string $tag
     *            标签名称
     * @param mixed $name
     *            插件名称
     * @return void
     */
    public function set($tag, $name)
    {
        if (! isset($this->tags[$tag])) {
            $this->tags[$tag] = [];
        }
        if (is_array($name)) {
            $this->tags[$tag] = array_merge($this->tags[$tag], $name);
        } else {
            $this->tags[$tag][] = $name;
        }
    }

    /**
     * 获取插件信息
     *
     * @param string $tag
     *            插件位置 留空获取全部
     * @return array
     */
    public function get($tag = '')
    {
        if (empty($tag)) {
            return $this->tags;
        } else {
            return $this->tags[$tag];
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
    public function trigger($tag, &$params = null)
    {
        if (! isset($this->tags[$tag])){
            return null;
        }
        foreach ($this->tags[$tag] as $name) {
            $this->exec($name, $tag, $params);
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