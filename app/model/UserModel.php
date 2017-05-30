<?php
namespace model;

use orc\Model;

class UserModel extends Model
{

    protected $tableName = 'user_dict';

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
        define('UID', $_SESSION['user']['id']);
        define('UNAME', $_SESSION['user']['username']);
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
    
    public function select($key='')
    {
        $this->where(['status'=>'Y']);
        return parent::select();    
    }
}