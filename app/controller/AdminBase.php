<?php
namespace Controller;

use orc\Controller;
use orc\Response;
use library\Validator;
use model\MenuModel;
use model\UserModel;

class AdminBase extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!IS_AJAX){
            $this->assign('leftMenu',MenuModel::ins()->getMenuHtml(MenuModel::ins()->getMenuTree()));    
        }
    }
    
    private function checkLogin()
    {
        $whiteList = config('loginWhiteList');
        $url = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
        if (in_array($url, $whiteList)){
            return;
        }
        
        if (! UserModel::single()->checkLogin()) {
            if (IS_AJAX) {
                $ontLoginCode = 100;
                $this->error('请登录',$ontLoginCode);
            }
            $this->redirect('/user/login');
        }
    }
    
    protected function redirect($url, $delay = 0)
    {
        Response::redirect($url, $delay);
    }

    protected function fetchFrame($file = '')
    {
        $this->assign('pageContent', $this->fetch($file));
        return $this->fetch('frame');
    }

    protected function showFrame($file = '')
    {
        Response::output($this->fetchFrame($file));
    }

    /**
     * 表单验证
     *
     * $config = array(
     * //"名字"为可选项，但强烈建议添加，用于生成完整错误提示信息
     * 'name:名字'=>array(
     * //如果没有notRequired规则，默认添加required规则
     * 'maxLen:10',
     * 'minLen:2',//自动获取提示内容
     * 'lt:23'=>'XX必须小于23',//手动指定提示内容
     * ),
     * 'age'=> 'gt:18',
     * );
     *
     * @param unknown $config            
     * @return string|NULL
     */
    public function responseValidate($config)
    {
        $errmsg = $this->validate($config);
        if ($errmsg) {
            $this->error($errmsg);
        }
    }

    public function validate($config)
    {
        foreach ($config as $field => $rules) {
            list ($name, $nameCN) = explode(':', $field);
            $data = I($name);
            if (! $nameCN) {
                $nameCN = $name;
            }
            if (! is_array($rules)) {
                $rules = (array) $rules;
            }
            $validator = Validator::ins($data, $nameCN);
            foreach ($rules as $k => $v) {
                if (is_string($k)) {
                    $ruleAndParams = $k;
                    $errorMsg = $v;
                } else {
                    $ruleAndParams = $v;
                    $errorMsg = null;
                }
                @list ($rule, $params) = explode(':', $ruleAndParams);
                $params = $params !== null ? explode(',', $params) : array();
                if ($errorMsg) {
                    $validator->setError($rule, $errorMsg);
                }
                call_user_func_array(array(
                    $validator,
                    $rule
                ), $params);
            }
            $errorMsg = $validator->getError();
            if ($errorMsg) {
                return $errorMsg;
            }
        }
        return null;
    }
}