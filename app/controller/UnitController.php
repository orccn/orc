<?php
namespace Controller;

use model\UnitModel;
use library\Tree;

class UnitController extends AdminBase
{

    function index()
    {
        $unitList = UnitModel::single()->select();
        $unitList = Tree::instance(['idField' => 'unit_code','parentField' => 'unit_ancer','levelField' => 'unit_level'])->getList($unitList,'00');
//         $treeList = Tree::instance(['idField' => 'unit_code','parentField' => 'unit_ancer','textField' => 'unit_name'])->getTree($unitList,'00');
//         print_pre($treeList);
        print_pre($unitList);
        exit;
        $this->assign('arr', $unitList);
        $this->showFrame('unit');
    }
}




















