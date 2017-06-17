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
        $this->showFrame('user');
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
    
    function resetPassword()
    {
        $userid = intval(I('userid'));
        $rs = UserModel::ins()->update(['door_pass'=>''],$userid);
        $this->success();
    }
    
    function lock()
    {
        return UserModel::ins()->update(['status'=>'Y'],intval(I('userid')));
        $this->success();
    }
}




















