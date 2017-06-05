<?php
namespace model;

use orc\Model;

class MenuModel extends Model
{

    protected $tableName = 'door_dict';
 
    public function where($where)
    {
        $where['is_delete'] = 0;
        return parent::where($where);
    }
}