<?php
namespace Controller;

class IndexController extends AdminBase
{

    function index()
    {
//         print_pre(array_merge(array('a'=>1,'b'=>1,'c'=>1),array_filter(array('a'=>null))));
//         exit;
        $arr = array(
            ['id'=>1,'name'=>'a','parent'=>0],
            ['id'=>2,'name'=>'b','parent'=>0],
            ['id'=>3,'name'=>'c','parent'=>0],
            ['id'=>4,'name'=>'aa','parent'=>1],
            ['id'=>5,'name'=>'ab','parent'=>1],
            ['id'=>6,'name'=>'aaa','parent'=>4],
            ['id'=>11,'name'=>'aaa','parent'=>6],
            ['id'=>7,'name'=>'aab','parent'=>4],
            ['id'=>8,'name'=>'ba','parent'=>2],
            ['id'=>9,'name'=>'bb','parent'=>2],
            ['id'=>10,'name'=>'ca','parent'=>3],
        );
//         echo Tree::single()->treeSelect($arr);
//         exit;
        return $this->fetchFrame();
    }
    private function treeList($arr,$parentId = 0)
    {
        $treeList = [];
        foreach ($arr as $v){
            if ($v['parent']==$parentId){
                $childList = $this->treeList($arr,$v['id']);
                $treeList = array_merge($treeList,array($v),$childList);
            }
        }
        return $treeList;
    }
    private function treeSelect($arr)
    {
        $str = '<select>';
        foreach ($arr as $v){
            
        }
    }
}




















