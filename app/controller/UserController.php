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
        $this->showFrame();
    }
    
    function ls()
    {
        $userList = UserModel::single()->getUserByUnit(I('unit_code'));
        Response::outputJson(['data'=>$userList]);
    }
    
    function detail()
    {
        $row = UserModel::ins()->getRow(I('userid'));
        if ($row) {
            $this->success($row);
        } else {
            $this->error('不存在此用户');
        }
    }
    
    function setunit()
    {
        $userid = intval(I('userid'));
        $units = I('units');
        UserModel::ins()->update(['units'=>$units],$userid);
        $this->success();
    }
    
    function reset_password()
    {
        $userid = intval(I('userid'));
        UserModel::ins()->update(['door_pass'=>''],$userid);
        $this->success();
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
    
    function del()
    {
//         $rs = UserModel::ins()->delete(intval(I('field_code')));
        $this->success();
    }
}




















