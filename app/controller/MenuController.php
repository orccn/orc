<?php
namespace Controller;

use library\Tree;
use model\MenuModel;

class MenuController extends AdminBase
{

    function index()
    {
        $menuList = MenuModel::single()->select('door_code');
        $option = [
            'idField' => 'door_code',
            'parentField' => 'door_parent'
        ];
        $sortMenu = Tree::instance($option)->getList($menuList, 0);
        $this->assign('menuList', $menuList);
        $this->assign('sortMenu', $sortMenu);
        $this->showFrame('menu');
    }

    function detail()
    {
        $code = I('code');
        $row = MenuModel::single()->where([
            'door_code' => $code
        ])->getRow();
        if ($row) {
            $row['is_menu'] = intval($row['is_menu']);
            $row['need_auth'] = intval($row['need_auth']);
            $this->success($row);
        } else {
            $this->error('不存在此功能');
        }
    }
    
    
    function edit()
    {
        $this->responseValidate([
            'door_name:功能名称' => 'maxLen:100',
            'door_code:功能编码' => ['notRequired','exists:MenuModel,door_code'],
            'door_parent:父级功能' => ['notRequired','exists:MenuModel,door_code'],
        ]);
        
        $arr = [];
        $code = intval(I('door_code'));
        $arr['door_name'] = I('door_name');
        $arr['door_url'] = I('door_url');
        $arr['door_parent'] = $parent = intval(I('door_parent'));
        $arr['door_level'] = $parent ? 2:1;
        $arr['is_menu'] = I('is_menu')=='true' ? 1 :0;
        $arr['need_auth'] = I('need_auth')=='true' ? 1 :0;
        
        //修改
        if ($code){
            if ($code==$parent){
                $this->error('不能选择自己作为上级');
            }
            $count = MenuModel::single()->where([
                'door_parent'=>$code
            ])->count();
            if ($parent&&$count){
                $this->error("[{$arr['door_name']}]下含有子功能，不能进行此操作");
            }
            MenuModel::single()->update($arr,['door_code'=>$code]);
            $this->success();
        }else{
            MenuModel::single()->insert($arr);
            $this->success();
        }
    }
    
    function del()
    {
        $this->responseValidate([
            'code:功能编码' => [
                'exists:MenuModel,door_code',
                'notExists:MenuModel,door_parent' => '此功能下含有子功能，不能进行此操作'
            ],
        ]);
        $rs = MenuModel::single()->delete(intval(I('code')));
        $this->success();
    }
}




















