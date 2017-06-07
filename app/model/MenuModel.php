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
    
//     public function where($where)
//     {
//         if (! isset($where['is_delete'])) {
//             $where['is_delete'] = 0;
//         }
//         return parent::where($where);
//     }
    
    public function delete($code)
    {
        return $this->where(['door_code'=>$code])->update(['is_delete'=>1]);
    }
}