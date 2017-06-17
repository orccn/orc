<?php
namespace Controller;

use model\UserModel;

class SelfController extends AdminBase
{
    
    function updatePassword()
    {
        if (REQUEST_METHOD != 'post') {
            return $this->showFrame();
        }
        $this->responseValidate([
            'pwd:原密码' => 'maxLen:30',
            'pwd1:新密码' => 'minLen:6|maxLen:30',
        ]);
        $pwd1 = I('pwd1');
        if ($pwd1 != I('pwd2')){
            $this->error('确认密码与新密码不一致');
        }
        $rs = UserModel::ins()->checkPassword(USER_CODE,I('pwd'));
        if (!$rs){
            $this->error('原密码不正确');
        }
        UserModel::ins()->updatePassword(UID,$pwd1);
        UserModel::ins()->reloadSesionUser();
        $this->success();
    }
    
    function login()
    {
        if (REQUEST_METHOD != 'post') {
            return $this->show();
        }
        $this->responseValidate([
            'user_code:登录号' => 'maxLen:30',
            'password:密码' => 'notRequired|maxLen:30'
        ]);
        $user = UserModel::single()->checkPassword(I('user_code'), I('password'));
        if (! $user) {
            $this->error('用户名或密码错误');
        }
        $_SESSION['user'] = $user;
        $this->success();
    }
    
    function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('/self/login');
    }
    
}




















