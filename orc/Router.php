<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 路由实现
 */
namespace orc;

class Router
{
    use traits\Instance;

    private $pathinfo = '';

    private $moudle = '';

    private $controller = '';

    private $action = '';

    private $params = [];

    private $ext = '';

    private $default = [
        'm' => '',
        'c' => 'index',
        'a' => 'index'
    ];

    private $controllerSuffix = 'Controller';

    public function __construct($pathInfo, $default = [])
    {
        if ($default) {
            $this->default = array_merge($this->default, $default);
        }
        $pathInfo = trim(trim($pathInfo), '/');
        if ($pathInfo) {
            $this->ext = strtolower(pathinfo($pathInfo, PATHINFO_EXTENSION));
            $this->parse($pathInfo);
        }
    }

    private function parse($pathInfo)
    {
        // remove ".html",".php".....
        $pathInfo = explode('/', trim(preg_replace("/\\..*$/", '', $pathInfo), ''));
        if (! $pathInfo || ! $pathInfo[0])
            return null;
            
            // set controller
        $this->controller = $pathInfo[0];
        
        // check controller
        if (! class_exists($this->getClassName())) {
            $this->moudle = $pathInfo[0];
            $pathInfo = array_slice($pathInfo, 1);
            if (! isset($pathInfo[0])) {
                $pathInfo[0] = $this->default['c'];
            }
            $this->controller = $pathInfo[0];
        }
        
        // set action
        if (isset($pathInfo[1])) {
            $this->action = $pathInfo[1];
        }
        
        // set paramseters
        if (isset($pathInfo[2])) {
            $this->params = array_slice($pathInfo, 1);
            if ($this->params) {
                $_GET = array_merge($_GET, $this->params);
            }
        }
    }

    /**
     * 请求分发
     */
    public function dispatch()
    {
        // 路由分配
        $className = $this->getClassName();
        if (! class_exists($className)) {
            Response::show404();
        }
        
        // check controller
        $controller = new \ReflectionClass($className);
        if ($controller->isAbstract()) {
            Response::show404();
        }
        
        // check action
        $action = $this->getActionName();
        if (! method_exists($className, $action)) {
            Response::show404();
        }
        $method = $controller->getMethod($action);
        if (! $method || ! $method->isPublic()) {
            Response::show404();
        }
        
        // dispatch
        define('MOUDLE_NAME', $this->getMoudleName());
        define('CONTROLLER_NAME', $this->getControllerName());
        define('ACTION_NAME', $this->getActionName());
        define('PAGE_TYPE', $this->getExt() ? $this->getExt() : config('defaultViewType'));
        define('URL_PAHT', ($this->moudle ? DS . $this->moudle : '') . DS . $this->controller . DS . $this->action);
        
        Hook::trigger('ctlInsBefore');
        $ctlObject = $controller->newInstance();
        Hook::trigger('ctlInsAfter');
        
        Hook::trigger('actionBefore');
        $content = $method->invoke($ctlObject);
        Hook::trigger('actionAfter');
        
        return $content;
    }

    /**
     * 获取controller包含命名空间的完整类名
     * $params string $name controller名字
     *
     * @return string
     */
    public function getClassName()
    {
        $class = $this->getControllerName() . $this->controllerSuffix;
        if ($this->getMoudleName()) {
            $class = $this->getMoudleName() . '\\' . $class;
        }
        $class = CONTROLLER_NAMESPACE . '\\' . $class;
        return $class;
    }

    /**
     * 获取Controller文件名(不包含扩展名)
     *
     * @return string
     */
    public function getControllerName()
    {
        if (empty($this->controller)) {
            $this->controller = $this->default['c'];
        }
        return underline2camel($this->controller);
    }

    /**
     * 获取文件夹名称
     *
     * @return string
     */
    public function getMoudleName()
    {
        if (empty($this->moudle)) {
            $this->moudle = $this->default['m'];
        }
        return $this->moudle;
    }

    /**
     * 获取动作名
     *
     * @return string
     */
    public function getActionName()
    {
        if (empty($this->action)) {
            $this->action = $this->default['a'];
        }
        return underline2camel($this->action, false);
    }

    /**
     * 获取pathinfo格式的url中的参数(不是querystring中的)
     *
     * @param int $index            
     */
    public function getParams($index = null)
    {
        if ($index === null) {
            return $this->params;
        }
        return $this->params[$index];
    }

    /**
     * 获取url后缀类型
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }
}