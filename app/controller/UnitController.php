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
        $unitList = Tree::ins($option)->getList($unitList, '00');
        $this->assign('unitList', $unitList);
        $this->showFrame('unit');
    }
    
    function tree()
    {
        header('Content-Type:application/json; charset=utf-8');
        echo '[{"id":"00","text":"\u5168\u9662"},{"id":"001","text":"\u624b\u5916\u79d1\u7cfb","children":[{"id":"001001","text":"\u624b\u5916\u4e00\u79d1","children":[{"id":"001001001","text":"\u624b\u4e00\u533b\u5e08\u7ec4A"},{"id":"001001002","text":"\u624b\u4e00\u533b\u5e08\u7ec4B"}]},{"id":"001002","text":"\u624b\u5916\u4e8c\u79d1","children":[{"id":"001002001","text":"\u624b\u4e8c\u533b\u5e08\u7ec4A"}]}]},{"id":"002","text":"\u9aa8\u5916\u79d1\u7cfb","children":[{"id":"002001","text":"\u9aa8\u5916\u4e00\u79d1","children":[{"id":"002001001","text":"\u9aa8\u4e00\u533b\u5e08\u7ec4A"},{"id":"002001002","text":"\u9aa8\u4e00\u533b\u5e08\u7ec4B"}]},{"id":"002002","text":"\u9aa8\u5916\u4e8c\u79d1"}]},{"id":"003","text":"\u533b\u6280\u79d1\u7cfb","children":[{"id":"003001","text":"\u68c0\u9a8c\u79d1"},{"id":"003002","text":"CT\u5ba4"}]}]';
        exit;
        $unitList = UnitModel::single()->select();
        $option = [
            'idField' => 'unit_code',
            'parentField' => 'unit_ancer',
            'levelField' => 'unit_level',
            'textField' => 'unit_name'
        ];
        $tree = Tree::ins($option)->getJSTreeData($unitList, '00');
        if (intval(I('flag'))==1){
            array_unshift($tree, ['id'=>'00','text'=>'全院']);
        }
        Response::outputJson($tree);
    }
}




















