<?php
namespace Controller;

class RoleController extends AdminBase
{

    function index()
    {
        $this->showFrame('role');
    }
    
    function setauth()
    {
        $doorCodes = I('door_codes');
    }
}




















