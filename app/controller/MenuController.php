<?php
namespace Controller;

use library\Tree;
use model\MenuModel;
use orc\Response;

class MenuController extends AdminBase
{

    function index()
    {
        $menuList = MenuModel::ins()->select('door_code');
        $option = [
            'idField' => 'door_code',
            'parentField' => 'door_parent'
        ];
        $sortMenu = Tree::ins($option)->getList($menuList, 0);
        $this->assign('sortMenu', $sortMenu);
        $this->assign('menuList', $menuList);
        $this->showFrame('menu');
    }

    function detail()
    {
        $row = MenuModel::ins()->getRow(I('door_code'));
        if ($row) {
            $row['is_menu'] = boolval($row['is_menu']);
            $row['need_auth'] = boolval($row['need_auth']);
            $row['has_field'] = boolval($row['has_field']);
            $this->success($row);
        } else {
            $this->error('不存在此功能');
        }
    }
    
    function tree()
    {
        $unitList = MenuModel::single()->select();
        $option = [
            'idField' => 'door_code',
            'parentField' => 'door_parent',
            'textField' => 'door_name'
        ];
        $tree = Tree::ins($option)->getJSTreeData($unitList, 0);
        Response::outputJson($tree);
    }
    
    function edit()
    {
        $this->responseValidate([
            'door_name:功能名称' => 'maxLen:100',
            'door_code:功能编码' => ['notRequired','exists:MenuModel,door_code'],
            'door_parent:父级功能' => ['notRequired','exists:MenuModel,door_code'],
        ]);
        
        $arr = [];
        $arr['door_name'] = I('door_name');
        $arr['door_url'] = I('door_url');
        $arr['door_parent'] = $parent = intval(I('door_parent'));
        $arr['door_level'] = $parent ? 2:1;
        $arr['is_menu'] = I('is_menu')=='true'||I('is_menu')==1 ? 1 :0;
        $arr['need_auth'] = I('need_auth')=='true'||I('need_auth')==1 ? 1 :0;
        $arr['has_field'] = I('has_field')=='true'||I('has_field')==1 ? 1 :0;
        
        //修改
        $code = intval(I('door_code'));
        if ($code){
            if ($code==$parent){
                $this->error('不能选择自己作为上级');
            }
            $count = MenuModel::ins()->where([
                'door_parent'=>$code
            ])->count();
            if ($parent&&$count){
                $this->error("[{$arr['door_name']}]下含有子功能，不能进行此操作");
            }
            MenuModel::ins()->update($arr,['door_code'=>$code]);
        }else{
            MenuModel::ins()->insert($arr);
        }
        $this->success();
    }
    
    function del()
    {
        $this->responseValidate([
            'door_code:功能编码' => [
                'exists:MenuModel,door_code',
                'notExists:MenuModel,door_parent' => '此功能下含有子功能，不能进行此操作'
            ],
        ]);
        $rs = MenuModel::ins()->delete(intval(I('door_code')));
        $this->success();
    }
}




















