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
    
    function edit()
    {
//         user_id:
//         name:
//         role:3
//         user_code:
//         sex:1
//         unit_code:0
//         idno:
//         certificate_no:
//         title:
        $this->responseValidate([
            'name:真实名称' => 'maxLen:60',
            'sex:性别' => 'maxLen:60',
            'user_code:登录号' => 'maxLen:30',
            'idno:身份证号' => 'notRequired|maxLen:30',
            'certificate_no:执业证书编码' => 'notRequired|maxLen:60',
            'title:职称' => 'notRequired|maxLen:60',
        ]);
        
        $arr = [];
        $arr['name'] = I('name');
        $arr['user_code'] = I('user_code');
        $arr['idno'] = I('idno');
        $arr['certificate_no'] = I('certificate_no');
        $arr['title'] = I('title');
        $arr['sex'] = intval(I('role')) ? 1 : 0;
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




















