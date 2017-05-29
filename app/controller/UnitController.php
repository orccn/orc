<?php
namespace Controller;

use model\UnitModel;
use orc\library\Tree;

class UnitController extends AdminBase
{

    function index()
    {
        $unitList = UnitModel::single()->select();
        $unitList = Tree::instance(['idField' => 'unit_code','parentField' => 'unit_ancer','levelField' => 'unit_level'])->getList($unitList,'00');
        $this->assign('arr', $unitList);
        $this->showFrame('unit');
    }
}




















