<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 控制器基类
 */
namespace orc;

class Controller
{

    private $view = null;

    public function __construct()
    {
        $this->view = View::instance();
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
        Response::output($this->fetch($file));
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
            if (MOUDLE_NAME){
                $file .= MOUDLE_NAME . '/';
            }
            $file .= strtolower(CONTROLLER_NAME) . '/' . ACTION_NAME;
        }
        return $this->view->render($file);
    }

    protected function error($msg, $code = 1)
    {
        $data = [
            'code' => $code,
            'msg' => $msg
        ];
        $this->exitJson($data);
    }

    protected function success($data = null, $more = null)
    {
        $msg = is_string($more) ? $more : 'ok';
        $data = [
            'code' => 0,
            'msg' => $msg,
            'data' => $data
        ];
        if (is_array($more)) {
            $data = array_merge($data, $more);
        }
        $this->exitJson($data);
    }

    protected function exitJson($data)
    {
        Response::exitJson($data, I('jsonp'));
    }
}