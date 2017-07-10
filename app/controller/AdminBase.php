<?php
namespace Controller;

use orc\Controller;
use orc\Response;
use library\Validator;
use model\UserModel;
use library\Comm;
use library\Auth;

class AdminBase extends Controller
{

    public function __construct()
    {
        parent::__construct();
        
        if (!in_array(URL_PATH_TRIM, config('loginWhiteList'))) {
            $this->checkLogin();
            Auth::init();
            if (! Comm::isAjax()) {
                $this->assign('leftMenu', Auth::getLeftMenuHtml());
            }
            $authRs = Auth::check(URL_PATH_TRIM);
            if ($authRs !== true) {
                $this->error($authRs !== false ?  $authRs : '权限不足');
            }
        }
    }

    private function checkLogin()
    {
        if (UserModel::single()->checkLogin()) {
            return ;
        }
        if (Comm::isAjax()) {
            $ontLoginCode = 100;
            $this->error('请登录', $ontLoginCode);
        }else{
            $this->redirect('/self/login');
        }
    }

    protected function error($msg, $code = 1)
    {
        if (Comm::isAjax()){
            parent::error($msg,$code);
        }else{
            $this->assign('msg',$msg);
            $this->show('common/error');
            exit;
        }
    }
    
    protected function redirect($url, $delay = 0)
    {
        di('res')->redirect($url, $delay);
    }

    protected function fetchFrame($file = '')
    {
        $this->assign('pageContent', $this->fetch($file));
        return $this->fetch('frame');
    }

    protected function showFrame($file = '')
    {
        di('res')->output($this->fetchFrame($file));
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
            if (!is_array($rules)){
                $rules = explode('|', $rules);
            }
            $validator = di('validator',$data, $nameCN);
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