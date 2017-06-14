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
        $doorCodes = I('door_codes');
        RoleModel::ins()->update(['menus'=>$doorCodes],$roleid);
        $this->success();
    }
}




















