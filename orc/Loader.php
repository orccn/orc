<?php
/**
 * @author lpywy728394@163.com
 * @date 2017-05-21
 * @desc 
 */
namespace orc;

class Loader
{

    private $paths = [];

    public function rigist($path = null)
    {
        if ($path) {
            $this->addPath($path);
        }
        $vendorAutoFile = '../vendor/autoload.php';
        if (is_file($vendorAutoFile)) {
            require $vendorAutoFile;
        }
        spl_autoload_register(['orc\Loader','autoload']);
    }

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
     * 自动加载方法
     *
     * @param string $class            
     */
    public function autoload($class)
    {
        foreach ($this->paths as $filePath) {
            $filePath .= str_replace('\\', '/', $class) . ".php";
            if (file_exists($filePath)) {
                require_once $filePath;
            }
        }
    }
}





