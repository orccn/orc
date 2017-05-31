<?php
namespace model;

use orc\Model;

class UnitModel extends Model
{

    protected $tableName = 'unit_list';
    
    public function select($key='')
    {
        $this->where(['valid_flag'=>'Y']);
        return parent::select();
    }
}