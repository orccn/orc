<?php
namespace model;

class FieldModel extends BaseModel
{

    protected $tableName = 'door_field_dict';

    protected $pk = 'field_code';

    public function __construct($tableName = '', $dbKey = '')
    {
        parent::__construct($tableName, $dbKey);
    }

    public function insert($data, $replace = false)
    {
        return parent::insertIncrField($data, $this->getPk(), $replace);
    }
}