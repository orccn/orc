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

    public function set($name, $value, $setSingleton = false)
    {
        $this->deps[$name] = [
            'value' => $value,
            'isSingleton' => $setSingleton
        ];
    }

    private function getObject($name,$args = [])
    {
        if (is_string($name)){
            
        }
    }
    
    public function get($name)
    {
        if (empty($this->deps[$name])){
            return false;
        }
        $args = array_slice(func_get_args(), 1);
        if ($this->deps[$name]['isSingleton']){
            if (!isset($this->singletons[$name])){
                $this->singletons[$name] = $this->getObject($name,$args);
            }
            return $this->singletons[$name];
        }
        
        
        
//         // 如果是匿名函数（Anonymous functions），也叫闭包函数（closures）
//         if ($className instanceof Closure) {
//             // 执行闭包函数，并将结果
//             return $className($this);
//         }
        
//         /** @var ReflectionClass $reflector */
//         $reflector = new ReflectionClass($className);
        
//         // 检查类是否可实例化, 排除抽象类abstract和对象接口interface
//         if (!$reflector->isInstantiable()) {
//             throw new Exception("Can't instantiate this.");
//         }
        
//         /** @var ReflectionMethod $constructor 获取类的构造函数 */
//         $constructor = $reflector->getConstructor();
        
//         // 若无构造函数，直接实例化并返回
//         if (is_null($constructor)) {
//             return new $className;
//         }
        
//         // 取构造函数参数,通过 ReflectionParameter 数组返回参数列表
//         $parameters = $constructor->getParameters();
        
//         // 递归解析构造函数的参数
//         $dependencies = $this->getDependencies($parameters);
        
//         // 创建一个类的新实例，给出的参数将传递到类的构造函数。
//         return $reflector->newInstanceArgs($dependencies);
        
    }
}
