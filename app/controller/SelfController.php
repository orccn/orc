<?php
namespace Controller;

use model\UserModel;

class SelfController extends AdminBase
{

    function initPassword()
    {
        if (REQUEST_METHOD != 'post') {
            return $this->showFrame();
        }
    }
    
    function updtePassword()
    {
        
    }
    
    function login()
    {
        if (REQUEST_METHOD != 'post') {
            return $this->show();
        }
        $this->responseValidate([
            'username:用户名' => 'notRequired|maxLen:30',
            'password:密码' => 'maxLen:30'
        ]);
        $user = UserModel::single()->checkPassword(I('username'), I('password'));
        if (! $user) {
            $this->error('用户名或密码错误');
        }
        $_SESSION['user'] = $user;
        $this->success();
    }
    
    function logout()
    {
        unset($_SESSION);
        $this->redirect('/user/login');
    }
    
}




















