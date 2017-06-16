<?php
namespace model;

class UserModel extends BaseModel
{

    protected $tableName = 'user_dict';
    
    protected $pk = 'user_id';

    public function __construct($tableName = '', $dbKey = '')
    {
        parent::__construct($tableName, $dbKey);
    }
    
    public function checkPassword($username, $password)
    {
        return array(
            'id' => 1,
            'username' => 'lpy',
            'passwd' => 1111
        );
        $user = $this->where([
            'username' => $username,
            'passwd' => $password
        ])->getRow();
        return empty($user) ? false : $user;
    }

    public function checkLogin()
    {
        if (empty($_SESSION['user'])) {
            return false;
        }
        define('UID', $_SESSION['user']['user_id']);
        define('UNAME', $_SESSION['user']['name']);
        return true;
    }
    
    public function getUserByUnit($unitCode)
    {
        $where = [];
        if (intval($unitCode)) {
            $where['unit_code']['like'] = "$unitCode%";
        }
        return $this->where($where)->select();
    }
}