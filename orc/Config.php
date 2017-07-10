<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 配置管理类
 */
namespace orc;

class Config
{

    private $config = [];

    private $paths = [];

    private $defaultFile = 'main';

    /**
     * 添加配置目录
     *
     * @param string $path
     *            配置路径
     */
    public function addPath($path)
    {
        if (is_array($path)) {
            $this->paths = array_merge($this->paths, array_map('fmtdir', $path));
        } else {
            $this->paths[] = fmtdir($path);
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
    public function get($key, $default = null)
    {
        $key = explode('.', $key, 2);
        if (! isset($key[1])) {
            array_unshift($key, $this->defaultFile);
        }
        $data = $this->load($key[0]);
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
    public function load($filename)
    {
        if (! isset($this->config[$filename])) {
            foreach ($this->paths as $path) {
                $data = parse_file($path . $filename . ".php");
                if ($data !== null) {
                    if (! isset($this->config[$filename])) {
                        $this->config[$filename] = [];
                    }
                    $this->config[$filename] = array_cover_recursive($this->config[$filename], $data);
                }
            }
            if (! isset($this->config[$filename])) {
                $this->config[$filename] = null;
            }
        }
        return $this->config[$filename];
    }
}
