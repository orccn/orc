<?php
namespace model;

use orc\Model;

class MenuModel extends Model
{

    protected $tableName = 'door_dict';

    public function __construct($tableName = '', $dbKey = '')
    {
        parent::__construct($tableName, $dbKey);
        $this->where(['is_delete'=>0]);
    }
    
    public function delete($code)
    {
        return $this->where(['door_code'=>$code])->update(['is_delete'=>1]);
    }
}