<?php
namespace Controller;

class IndexController extends AdminBase
{

    function index()
    {
        $arr = array(
            ['id'=>1,'name'=>'a','parent_id'=>0,'level'=>1],
            ['id'=>2,'name'=>'b','parent_id'=>0,'level'=>1],
            ['id'=>3,'name'=>'c','parent_id'=>0,'level'=>1],
            ['id'=>4,'name'=>'d','parent_id'=>1,'level'=>2],
            ['id'=>5,'name'=>'e','parent_id'=>1,'level'=>2],
            ['id'=>6,'name'=>'f','parent_id'=>4,'level'=>3],
            ['id'=>7,'name'=>'g','parent_id'=>2,'level'=>2],
            ['id'=>8,'name'=>'h','parent_id'=>3,'level'=>2],
        );
        print_pre($this->treeList($arr));
        exit;
        $conn = oci_connect('system', 'lpy123456', 'LIUPENGYU');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $stid = oci_parse($conn, strtoupper("select * from test"));
        $rs = oci_execute($stid);
        var_dump(oci_fetch_array($stid,OCI_BOTH));
        
        //return $this->fetch();
    }
    private function treeList($arr,$parentId = 0)
    {
        $treeList = [];
        foreach ($arr as $v){
            if ($v['parent_id']==$parentId){
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




















