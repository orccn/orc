<?php
namespace Controller;

use library\Comm;
use model\UserModel;
use orc\Response;
class OpaccountController extends AdminBase
{

    function index()
    {
        //http://blog.csdn.net/maxoracle/article/details/52535597
        if (Comm::isAjax()){
            $userList = UserModel::single()->getUserByUnit(I('unit_code'));
            Response::exitJson([
                'data' => $userList
            ]);
        }else{
            $this->showFrame();
        }
    }
}




















