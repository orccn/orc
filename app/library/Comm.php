<?php
/**
 * @author lpywy728394@163.com
 * @date 2017-06-18
 */
namespace library;

use model\RoleModel;
class Comm
{
    public static function checkAuth($suburl,$roleid=USER_ROLE)
    {
        if ($roleid==1 || in_array($suburl, config('authWhiteList'))) {
            return true;
        }
        if ($roleid!=1 && in_array($suburl, config('sysMenu'))) {
            return '权限不足，只有超级用户才拥有此权限！';
        }
        $menuList = RoleModel::ins()->getMenuList($roleid);
        $urls = array_unique(array_column($menuList, 'door_url'));
        return in_array($suburl, $urls);
    }
    
    public static function isAjax($key = 'ajax')
    {
        return IS_AJAX || I($key);
    }
}