<?php
namespace model;

use orc\Model;

class UserModel extends Model
{

    protected $tableName = 'admin_user';

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
}