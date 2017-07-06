<?php
namespace model;

use orc\Model;

class BaseModel extends Model
{

    public function __construct($tableName = '', $dbKey = '')
    {
        parent::__construct($tableName, $dbKey);
    }

    public function getMax($field)
    {
        $rs = $this->fields("max($field) as max_val")->select();
        $max = empty($rs[0]['max_val']) ? null : intval($rs[0]['max_val']);
        return $max;
    }

    public function maxNext($field)
    {
        $max = $this->getMax($field);
        return intval($max) + 1;
    }

    public function insertIncrField($data, $field, $replace = false)
    {
        if (! isset($data[$field])) {
            $data[$field] = $this->maxNext($field);
        }
        $rs = parent::insert($data, $replace);
        return $rs ? $data[$field] : false;
    }

    public function datatable()
    {
        $start = intval(I('start'));
        $len = intval(I('length'));
        if ($len > 1000) {
            $len = 1000;
        }
        $obj = clone $this;
        $data = $this->limit($start.','.$len)->select();
        $allCount = $this->count();
        $filterCount = $obj->count();
        $arr = [
            'data' => $data,
            'all_count' => $allCount,
            'filter_count' => $filterCount
        ];
        return $arr;
    }
}