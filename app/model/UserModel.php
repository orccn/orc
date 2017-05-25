<?php
namespace model;

use library\SSO;

class UserModel extends BaseModel
{

    protected $tableName = 'base_user';

    /**
     * 登录验证
     *
     * @param unknown $username            
     * @param unknown $password            
     * @return Ambigous <multitype:, unknown>
     */
    public function checkPassword($username, $password)
    {
        $user = $this->where(['username' => $username])->getRow();
        if (! $user) {
            return USER_ERR_NOT_EXISTS;
        }
        if (! password_verify($password, $user['password'])) {
            return USER_ERR_PASSWORD_WRONG;
        }
        
        $sso = new SSO();
        $user['loginTime'] = time();
        $rs = $sso->setTokenCache($user, true);
        unset($user['password']);
        return $user;
    }

    public function resetPassword($username, $password)
    {}

    /**
     * 获取加密后的密码
     *
     * @param unknown $password            
     */
    protected function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}