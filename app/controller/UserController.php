<?php
namespace Controller;

class UserController extends AdminBase
{

    function login()
    {
        if (REQUEST_METHOD != 'post') {
            return $this->show();
        }
        $username = I('username');
        $password = I('password');
        $this->responseValidate([
            'username:用户名' => 'maxLen:1'
        ]);
    }
}




















