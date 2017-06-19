<?php
namespace model;

use library\Tree;

class MenuModel extends BaseModel
{

    protected $tableName = 'door_dict';
    
    protected $pk = 'door_code';
    
    protected $suburl = '';

    public function __construct($tableName = '', $dbKey = '')
    {
        parent::__construct($tableName, $dbKey);
        $this->suburl = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
    }
    
    public function insert($data, $replace = false)
    {
        return parent::insertIncrField($data, $this->getPk(), $replace);
    }
}