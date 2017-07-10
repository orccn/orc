<?php
/**
 * @author lpywy728394@163.com
 * @date 2017-06-18
 */
namespace library;

use model\RoleModel;
use model\MenuModel;
use orc\library\Dependency;

class Auth
{

    public static $roleid = 0;
    
    public static $roles = [];

    public static $menus = [];

    public static function init($roleid = USER_ROLE)
    {
        self::$roleid = $roleid;
        self::$roles = RoleModel::ins()->select('role_id');
        self::$menus = MenuModel::ins()->order('door_sort')->select();
    }
    
    public static function check($suburl)
    {
        if (self::$roleid == 1 || in_array($suburl, config('auth.whiteList'))) {
            return true;
        }
        if (self::$roleid != 1 && in_array($suburl, config('auth.sysMenu'))) {
            return '权限不足，只有超级用户才拥有此权限！';
        }
        return in_array($suburl, self::getAuthURLs());
    }

    public static function getAuthURLs()
    {
        $authURLs = $depURLs = [];
        $menuIDs = self::getRoleMenuIDs();
        foreach (self::$menus as $v) {
            if ((! $v['need_auth'] || in_array($v['door_code'], $menuIDs)) && !in_array( $v['door_url'], $authURLs)){
                $authURLs[] = $v['door_url'];
            }
        }
        $dependency= Dependency::ins(config('auth.dependency'))->expand();
        foreach ($authURLs as $v){
            if (isset($dependency[$v])){
                $depURLs = array_merge($depURLs,$dependency[$v]);
            }
        }
        $authURLs = array_unique(array_merge($authURLs,$depURLs));
        return $authURLs;
    }
    
    private static function getRoleMenuIDs()
    {
        return array_filter(explode(',', self::$roles[self::$roleid]['menus']));
    }
    
    public static function getLeftMenuHtml()
    {
        $arr = [];
        $menuIDs = self::getRoleMenuIDs();
        foreach (self::$menus as $v) {
            if (! $v['is_menu']) {
                continue;
            }
            if (self::$roleid > 1) {
                if (! $v['need_auth'] || in_array($v['door_code'], $menuIDs)){
                    $arr[] = $v;
                }
            } else {
                $arr[] = $v;
            }
        }
        $option = [
            'idField' => 'door_code',
            'parentField' => 'door_parent'
        ];
        $menuTree = di('tree',$option)->getTree($arr, 0);
        $html = self::createLeftMenuHtml($menuTree);
        return $html;
    }

    private static function createLeftMenuHtml($data)
    {
        $html = '';
        foreach ($data as $v) {
            
            $arrow = empty($v['child']) ? '' : '<span class="arrow"></span>';
            $childHtml = $arrow ? '<ul class="sub-menu">' . self::createLeftMenuHtml($v['child']) . '</ul>' : '';
            $href = empty($v['door_url']) ? 'javascript:;' : '/' . ltrim($v['door_url'], '/');
            $selected = $openClass = '';
            if ($v['door_url'] == URL_PATH_TRIM) {
                $selected = '<span class="selected"></span>';
                $openClass = ' active open ';
            }
            
            $html .= '<li class="nav-item ' . $openClass . '"><a href="' . $href . '" class="nav-link nav-toggle">';
            $html .= '<i class="icon-layers"></i><span class="title">' . $v['door_name'] . '</span>';
            $html .= $selected . $arrow . '</a>' . $childHtml . '</li>';
        }
        return $html;
    }
}