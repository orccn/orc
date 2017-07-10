<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 控制器基类
 */
namespace orc;

class Controller
{
    protected $view = null;
    
    protected $res= null;

    public function __construct()
    {
        $this->view = di('view');
        $this->res = di('res');
    }

    /**
     * 模板变量赋值
     *
     * @access public
     * @param mixed $name            
     * @param mixed $value            
     */
    protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);
        return $this;
    }

    /**
     * 输出内容
     *
     * @param string $file            
     * @param array $vars            
     * @return null
     */
    protected function show($file = '')
    {
        $this->res->output($this->fetch($file));
    }

    /**
     * 获取内容
     *
     * @param string $file            
     * @param array $vars            
     * @return string
     */
    protected function fetch($file = '')
    {
        if (! $file) {
            if (MOUDLE_NAME) {
                $file .= MOUDLE_NAME . '/';
            }
            $file .= strtolower(CONTROLLER_NAME) . '/' . ACTION_NAME;
        }
        return $this->view->render($file);
    }

    protected function error($msg, $code = 1)
    {
        $this->res->error($msg, $code);
    }

    protected function success($data = null, $more = null)
    {
        $this->res->success($data, $more);
    }

    protected function exitJson($data)
    {
        $this->res->exitJson($data, I('jsonp'));
    }
}