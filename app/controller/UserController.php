<?php
namespace Controller;
use model\UserModel;
use model\AppModel;
use library\SSO;
use orc\Response;
use orc\URL;
use orc\Controller;

class UserController extends Controller
{
    public function login()
    {
        $appId = intval(I('appid'));
        $redirect = I('redirect');
        $submit = I('submit');
        
        if ($submit) {
            $username = I('username');
            $password = I('password');
            $userModel = new UserModel();
            $user = $userModel->checkPassword($username, $password);
            if (is_int($user)) {
                if ($user == USER_ERR_NOT_EXISTS) {
                    $this->error('该用户不存在', USER_ERR_NOT_EXISTS);
                }
                if ($user == USER_ERR_PASSWORD_WRONG) {
                    $this->error('用户名与密码不匹配', USER_ERR_PASSWORD_WRONG);
                }
            }
            
            $sso = new SSO();
            $token = $sso->makeToken($user['id'], $appId);
            if (! $token) {
                $this->ssoError($sso);
            }
            Response::setcookie('token', $token, 3600);
            $url = $this->getRedirect($user['id'], $appId, $redirect);
            $this->success($url);
        } else {
            $rs = null;
            if (isset($_COOKIE['token'])) {
                $sso = new SSO();
                $rs = $sso->checkToken($_COOKIE['token']);
            }
            if ($rs) {
                $url = $this->getRedirect($rs['uid'], $appId, $redirect);
                echo $url;
            } else {
                echo '这是页面';
            }
        }
    }
    /**
     * 获取跳转地址
     *
     * @param unknown $uid            
     * @param unknown $appId            
     * @param unknown $redirect            
     * @return string
     */
    private function getRedirect($uid, $appId, $redirect)
    {
        if ($appId) {
            $appModel = new AppModel();
            $app = $appModel->getRow($appId);
            if (! $app) {
                $this->error("不存在ID为{$appId}的系统.", USER_ERR_APP_NOT_EXISTS);
            }
            
            $sso = new SSO();
            $code = $sso->makeCode($uid, $appId);
            if (! $code) {
                $this->ssoError($sso);
            }
            $params = array('code' => $code);
            if ($redirect) {
                $params['redirect'] = urlencode($redirect);
            }
            $url = URL::setURLQuery($params, $app['callback']);
        } else {
            $url = $redirect ? $redirect : '/user/index';
        }
        return $url;
    }
    public function logout()
    {
        $sso = new SSO();
        $token = I('token') ? I('token') : $_COOKIE['token'];
        $rs = $sso->delTokenCache($token);
        if (! $rs) {
            $this->ssoError($sso);
        }
        var_dump($rs);
    }
    /**
     * 验证token是否正确
     */
    public function checkToken()
    {
        $token = I('token');
        if (! $token) {
            $this->error('Token不能为空', SSO_ERR_TOKEN_EMPTY);
        }
        $sso = new SSO();
        $rs = $sso->checkToken($token);
        if (! $rs) {
            $this->ssoError($sso);
        }
        $this->success();
    }
    public function checkCode()
    {
        $code = I('code');
        if (! $code) {
            $this->error('Code不能为空', SSO_ERR_CODE_EMPTY);
        }
        $sso = new SSO();
        $rs = $sso->checkCode($code);
        if (! $rs) {
            $this->ssoError($sso);
        }
        $this->success($rs);
    }
    private function ssoError($sso)
    {
        $this->error($sso->getErrorMsg(), $sso->getErrorCode());
    }
}