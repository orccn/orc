<?php
namespace model;

class UnitModel extends BaseModel
{

    protected $tableName = 'unit_list';
    
    protected $pk = 'unit_code';
    
    public function select($key='')
    {
        $this->where(['valid_flag'=>'Y']);
        return parent::select();
    }
}