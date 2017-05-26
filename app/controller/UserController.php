<?php
namespace Controller;

class UserController extends AdminBase
{
    function login()
    {
        if (!I('submit')){
            return $this->show();
        }
        $username = I('username');
        $password = I('password');
        
    }
    
}




















