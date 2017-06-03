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
            'parentField' => 'door_parent',
        ];
        $menuList = Tree::instance($option)->getList($menuList, 0);
        print_pre($menuList);
        exit;
        $this->assign('menuList', $menuList);
        $this->showFrame('menu');
    }
}




















