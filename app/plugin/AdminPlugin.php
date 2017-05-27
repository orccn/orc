<?php
namespace plugin;

use model\UserModel;
use orc\Response;

class AdminPlugin
{
    public function actionBefore()
    {
        $whiteList = config('loginWhiteList');
        $url = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
        if (in_array($url, $whiteList)){
            return;
        }
        
        $isLogin = UserModel::single()->checkLogin();
        if (! $isLogin) {
            if (IS_AJAX) {
                $ontLoginCode = 100;
                Response::error('请登录',$ontLoginCode);
            }
            Response::redirect('/user/login');
        }
    }
    
}