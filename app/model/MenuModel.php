<?php
namespace model;

use orc\Model;

class MenuModel extends Model
{

    protected $tableName = 'door_dict';
 
    public function where($where)
    {
        $where['valid_flag'] = 'Y';
        return parent::where($where);
    }
}