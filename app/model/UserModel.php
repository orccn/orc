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

    public function checkPassword($userCode, $password)
    {
        $user = $this->where([
            'user_code' => $userCode
        ])->getRow();
        if (empty($user)) {
            return false;
        }
        if (empty($user['door_pass'])&&$userCode==$password){
            $this->updatePassword($user['user_id'], $password);
            $user = $this->getRow($user['user_id']);
            return $user;
        }
        if(password_verify($password, $user['door_pass'])){
            return $user; 
        }
        return false;
    }

    private function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function updatePassword($userid, $password)
    {
        return $this->update([
            'door_pass' => $this->encryptPassword($password)
        ], $userid);
    }

    public function reloadSesionUser()
    {
        $_SESSION['user'] = $this->getRow(UID);
    }

    public function checkLogin()
    {
        if (empty($_SESSION['user'])) {
            return false;
        }
        define('UID', $_SESSION['user']['user_id']);
        define('REALNAME', $_SESSION['user']['name']);
        define('USER_ROLE', $_SESSION['user']['role']);
        define('USER_CODE', $_SESSION['user']['user_code']);
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