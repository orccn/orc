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
    
    function updatePassword()
    {
        if (REQUEST_METHOD != 'post') {
            return $this->showFrame();
        }
    }
    
    function login()
    {
        if (REQUEST_METHOD != 'post') {
            return $this->show();
        }
        $this->responseValidate([
            'username:用户名' => 'maxLen:30',
            'password:密码' => 'notRequired|maxLen:30'
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
        $this->redirect('/self/login');
    }
    
}




















