<?php
namespace model;

class RoleModel extends BaseModel
{

    protected $tableName = 'role_list';
    
    protected $pk = 'role_id';
    
    public function getMenuList($roleid)
    {
        $menuIDs = $this->getMenuIDs($roleid);
        if (!$menuIDs){
            return [];
        }
        $menuList = MenuModel::ins()->where(['door_code'=>$menuIDs])->select('door_code');
        return $menuList;
    }
}