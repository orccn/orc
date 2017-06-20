<?php
namespace Controller;

use model\UserModel;
use orc\Response;
use library\Comm;
use model\UnitModel;

class UserController extends AdminBase
{

    function index()
    {
        if (Comm::isAjax()){
            $userList = UserModel::single()->getUserByUnit(I('unit_code'));
            Response::exitJson(['data'=>$userList]);
        }else{
            $userList = UserModel::single()->select();
            $endUnitList = array_column(UnitModel::ins()->where(['end_flag'=>'Y'])->select(), 'unit_name','unit_code');
            $this->assign('userList',$userList);
            $this->assign('endUnitList',$endUnitList);
            $this->showFrame('user');
        }
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




















