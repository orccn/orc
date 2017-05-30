<?php
namespace Controller;

use model\UnitModel;
use library\Tree;
use orc\Response;

class UnitController extends AdminBase
{

    function index()
    {
        $unitList = UnitModel::single()->select();
        $option = [
            'idField' => 'unit_code',
            'parentField' => 'unit_ancer',
            'levelField' => 'unit_level',
            'textField' => 'unit_name'
        ];
        $unitList = Tree::instance($option)->getList($unitList, '00');
        $this->assign('unitList', $unitList);
        $this->showFrame('unit');
    }
    
    function tree()
    {
        $unitList = UnitModel::single()->select();
        $option = [
            'idField' => 'unit_code',
            'parentField' => 'unit_ancer',
            'levelField' => 'unit_level',
            'textField' => 'unit_name'
        ];
        $tree = Tree::instance($option)->getJSTreeData($unitList, '00');
        array_unshift($tree, ['id'=>'00','text'=>'全院']);
        Response::outputJson($tree);
    }
}




















