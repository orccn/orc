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
    
    public function getFieldList($doorCode)
    {
        return $this->where(['door_code' => $doorCode])->order('rank')->select();
    }
    
    public function getFieldListByUrl($url)
    {
        $row = MenuModel::single()->where([
            'door_url' => $url,
            'has_field' => 1
        ])->getRow();
        if (! $row) {
            return false;
        }
        return $this->getFieldList($row['door_code']);
    }
}