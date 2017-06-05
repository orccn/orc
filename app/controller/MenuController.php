<?php
namespace Controller;

use library\Tree;
use model\MenuModel;

class MenuController extends AdminBase
{

    function index()
    {
        $menuList = MenuModel::single()->select();
        $option = [
            'idField' => 'door_code',
            'parentField' => 'door_parent'
        ];
        $menuList = Tree::instance($option)->getList($menuList, 0);
        $this->assign('menuList', $menuList);
        $this->showFrame('menu');
    }

    function detail()
    {
        $code = I('code');
        $row = MenuModel::single()->where([
            'door_code' => $code
        ])->getRow();
        if ($row) {
            $this->success($row);
        } else {
            $this->error('不存在此功能');
        }
    }
}




















