<?php
namespace Controller;

use orc\Controller;

class IndexController extends Controller
{
    function index()
    {
        foreach ($this->test() as $v){
            
        }
        
//         $di = new DI();
//         di()->set('test', 'orc\Config');
//         di()->get('test',22);
//         exit;
        
        return $this->fetch();
        
    }
    function test(){
        echo 1;
        return [1,2,3,4,5];
    }
}
