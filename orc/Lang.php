<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 国际化
 */
namespace orc;

class Lang
{

    protected $config = [];

    protected $path = null;

    protected $lang = 'zh-cn';

    protected $defaultFile = 'main';

    /**
     * 设置配置目录
     *
     * @param string $path
     *            配置路径
     */
    public function addPath($path)
    {
        $this->path = fmtdir($path);
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
    public function get($key = '', $params = null)
    {
        if ($params !== null) {
            $params = (array) $params;
        }
        $key = explode('.', $key, 2);
        if (! isset($key[1])) {
            array_unshift($key, $this->defaultFile);
        }
        $data = $this->load($key[0]);
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
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * 获取配置数据
     *
     * @param string $filename            
     * @return mixed
     */
    public function load($filename)
    {
        $file = $this->path . $this->lang . '/' . $filename . ".php";
        if (! isset($this->config[$filename])) {
            $this->config[$filename] = parse_file($file);
        }
        return $this->config[$filename];
    }
}
