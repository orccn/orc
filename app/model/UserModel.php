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
        $user = $this->where([
            'user_id' => $username
        ])->getRow();
        if (empty($user) || (! password_verify($password, $user['door_pass']) && ! empty($user['door_pass']))) {
            return false;
        }
        return $user;
    }

    private function encrptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function updatePassword($userid, $password)
    {
        return $this->update([
            'door_pass' => $this->encrptPassword($password)
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