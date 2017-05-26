<?php
namespace Controller;

use model\UserModel;
class UserController extends AdminBase
{

    function login()
    {
        if (REQUEST_METHOD != 'post') {
            return $this->show();
        }
        $this->responseValidate([
            'username:用户名' => 'maxLen:30',
            'password:密码' => 'maxLen:30',
        ]);
        $username = I('username');
        $password = I('password');
        $user = UserModel::single()->where(['username'=>$username,'passwd'=>$password])->getRow();
        print_r($user);
        exit;
    }
}




















