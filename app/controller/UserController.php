<?php
namespace Controller;

use model\UserModel;
use orc\Response;

class UserController extends AdminBase
{

    function index()
    {
        $userList = UserModel::single()->select();
        $this->assign('userList',$userList);
//         print_pre($userList);
        $this->showFrame();
    }
    
    function ls()
    {
        $userList = UserModel::single()->getUserByUnit(I('unit_code'));
        Response::outputJson(['data'=>$userList]);
    }
    
    function login()
    {
        if (REQUEST_METHOD != 'post') {
            return $this->show();
        }
        $this->responseValidate([
            'username:用户名' => 'maxLen:30',
            'password:密码' => 'maxLen:30'
        ]);
        $user = UserModel::single()->checkPassword(I('username'), I('password'));
        if (! $user) {
            $this->error('用户名或密码错误');
        }
        $_SESSION['user'] = $user;
        $this->success();
    }
}




















