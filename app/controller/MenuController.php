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
            'parentField' => 'door_ancer',
            'levelField' => 'door_level',
            'textField' => 'door_name'
        ];
        $menuList = Tree::instance($option)->getList($menuList, '00');
        $this->assign('menuList', $menuList);
        $this->showFrame('menu');
    }
}




















