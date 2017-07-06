<?php
namespace Controller;

use orc\Controller;
use orc\DI;

class IndexController extends Controller
{
    function index()
    {
        $di = new DI();
        $di->set('test', 'orc\Config');
        $di->get('test',22);
        exit;
        
        return $this->fetch();
        
    }
}
