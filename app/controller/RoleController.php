<?php
namespace Controller;

use model\RoleModel;
class RoleController extends AdminBase
{

    function index()
    {
        $this->showFrame('role');
    }
    
    function detail()
    {
        $row = RoleModel::ins()->getRow(I('roleid'));
        if ($row) {
            $this->success($row);
        } else {
            $this->error('不存在此角色');
        }
    }
    
    function setauth()
    {
        $roleid = intval(I('roleid'));
        if ($roleid==1){
            $this->success();
        }
        if ($roleid==2&&$_SESSION['role']!=1){
            $this->error('只有超级用户才能修改管理者权限');
        }
        if ($roleid==2&&($_SESSION['role']!=1||$_SESSION['role']!=2)){
            $this->error('只有超级用户和管理员才能修改此角色权限');
        }
        $doorCodes = I('door_codes');
        RoleModel::ins()->update(['menus'=>$doorCodes],$roleid);
        $this->success();
    }
}




















