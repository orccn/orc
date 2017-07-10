<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-07-06
 * @desc 依赖注入容器
 */
namespace orc;

class DI
{

    private $deps = [];

    private $singletons = [];

    public static function ins()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }
    
    public function set($alias, $concrete, $setSingleton = false)
    {
        $this->deps[$alias] = [
            'concrete' => $concrete,
            'isSingleton' => $setSingleton
        ];
    }

    public function singleton($alias, $concrete)
    {
        $this->set($alias, $concrete, true);
    }

    private function getObject($concrete, $args = [])
    {
        if ($concrete instanceof \Closure) {
            return call_user_func_array($concrete, $args);
        }
        if (is_object($concrete)) {
            return $concrete;
        }
        if (is_string($concrete)) {
            $reflector = new \ReflectionClass($concrete);
            $constructor = $reflector->getConstructor();
            if (is_null($constructor) || empty($constructor->getParameters())) {
                return new $concrete();
            } else {
                return $reflector->newInstanceArgs($args);
            }
        }
        throw new Exception("unknown concrete type.");
    }

    public function get($alias, $args = [])
    {
        if (empty($this->deps[$alias])) {
            throw new Exception("alias $alias not exists.");
        }
        $concrete = $this->deps[$alias]['concrete'];
        if ($this->deps[$alias]['isSingleton']) {
            if (! isset($this->singletons[$alias])) {
                $this->singletons[$alias] = $this->getObject($concrete);
            }
            return $this->singletons[$alias];
        } else {
            return $this->getObject($concrete, $args);
        }
    }
}
