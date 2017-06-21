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
        $role = intval(I('role'));
        $unitCode = I('unit_code');
        if ($role>3 || $role<1){
            $this->error('此角色不存在');
        }
        if (USER_ROLE>=$role){
            $this->error('没有权限添加角色');
        }
        $this->responseValidate([
            'name:真实名称' => 'maxLen:60',
            'sex:性别' => 'maxLen:60',
            'unit_code:所属单元' => ['exists:UnitModel'],
            'user_code:登录号' => 'maxLen:30',
            'idno:身份证号' => 'notRequired|maxLen:30',
            'certificate_no:执业证书编码' => 'notRequired|maxLen:60',
            'title:职称' => 'notRequired|maxLen:60',
        ]);
        $unit = UnitModel::ins()->where(['end_flag'=>'Y'])->getRow($unitCode);
        if (! $unit){
            $this->error('不存在code为{'.$unitCode.'}的末级单元');
        }
        $arr = [];
        $arr['name'] = I('name');
        $arr['role'] = $role;
        $arr['user_code'] = I('user_code');
        $arr['idno'] = I('idno');
        $arr['certificate_no'] = I('certificate_no');
        $arr['title'] = I('title');
        $arr['sex'] = intval(I('role')) ? 1 : 0;
        
        //修改
        $userid = intval(I('user_id'));
        if ($userid){
            UnitModel::ins()->update($arr,$userid);
        }else{
            UnitModel::ins()->insert($arr);
        }
        $this->success();
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




















