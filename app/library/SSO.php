<?php
namespace library;

use orc\library\Crypt;
class SSO
{
    use \orc\traits\Instance;
    
    protected $cache        = null;
    protected $errorCode    = 0;
    protected $codeKey      = 'sso:code:%s';
    protected $codeExpire   = 15;
    protected $tokenKey     = 'sso:user:%d';
    protected $tokenSalt    = 'F51#g>=0K7!F4)M9*';
    protected $tokenMinExpire = 0;
    protected $tokenMaxExpire = 0;
    
    const ERR_TOKEN_EXCEED_MIN  = 1001;  //超过token生存时间
    const ERR_TOKEN_EXCEED_MAX  = 1002;  //超过token最大生存时间
    const ERR_TOKEN_INVALID     = 1003;  //token无效
    const ERR_TOKEN_RELOGIN     = 1004;  //重新登录导致token失效
    const ERR_PASSWORD_CHANGE   = 1005;  //密码修改导致token失效
    const ERR_CODE_NOT_EXISTS   = 1006;  //code不存在
    const ERR_CACHE_SET         = 1007;  //set失败
    const ERR_CACHE_DEL         = 1008;  //del失败
    
    public function __construct()
    {
        $this->init();
        $this->cache = cache('redisDefault');
    }

    /**
     * 初始化
     */
    private function init()
    {
        $c = config('sso');
        if (! $c) {
            $this->tokenMinExpire = 86400 * 7;
            $this->tokenMaxExpire = 86400 * 30;
        } else {
            if (isset($c['codeKey'])) {
                $this->codeKey = $c['codeKey'];
            }
            if (isset($c['codeExpire'])) {
                $this->codeExpire = $c['codeExpire'];
            }
            if (isset($c['tokenKey'])) {
                $this->tokenKey = $c['tokenKey'];
            }
            if (isset($c['tokenSalt'])) {
                $this->tokenSalt = $c['tokenSalt'];
            }
            if (isset($c['tokenMinExpire'])) {
                $this->tokenMinExpire = $c['tokenMinExpire'];
            }
            if (isset($c['tokenMaxExpire'])) {
                $this->tokenMaxExpire = $c['tokenMaxExpire'];
            }
        }
    }

    /**
     * 设置错误代码
     * 
     * @param unknown $errorCode            
     */
    private function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * 获取错误代码
     * 
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * 获取错误内容
     */
    public function getErrorMsg()
    {
        $arr = array(self::ERR_TOKEN_EXCEED_MIN => 'Token已过期',self::ERR_TOKEN_EXCEED_MAX => 'Token已超过最大生存时间',self::ERR_TOKEN_INVALID => 'Token无效',self::ERR_TOKEN_RELOGIN => '你在其他地方登录了,请重新登录',self::ERR_PASSWORD_CHANGE => '你的密码已经更改,请重新登录',self::ERR_CODE_NOT_EXISTS => 'Code已过期',self::ERR_CACHE_SET => '缓存设置失败',self::ERR_CACHE_DEL => '缓存删除失败');
        return $arr[$this->errorCode];
    }

    /**
     * 获取给子系统code
     * 
     * @param unknown $uid            
     * @param unknown $appId            
     */
    public function makeCode($uid, $appId)
    {
        $code = md5(uniqid(microtime(true), true) . mt_rand());
        $rs = $this->cache->set($this->getCodeKey($code), array('uid' => $uid,'appId' => $appId), $this->codeExpire);
        if ($rs) {
            return $code;
        } else {
            $this->setErrorCode(self::ERR_CACHE_SET);
            return false;
        }
    }

    /**
     * 验证code，成功后返回token
     * 
     * @param unknown $code            
     */
    public function checkCode($code)
    {
        $key = $this->getCodeKey($code);
        $rs = $this->cache->get($key);
        if (! $rs) {
            $this->setErrorCode(self::ERR_CODE_NOT_EXISTS);
            return false;
        }
        $this->cache->del($key);
        $token = $this->makeToken($rs['uid'], $rs['appId']);
        return $token;
    }

    /**
     * 获取code的key值
     * 
     * @param unknown $code            
     * @return string
     */
    private function getCodeKey($code)
    {
        return sprintf($this->codeKey, $code);
    }

    /**
     * 获取登录token
     * 
     * @param unknown $uid            
     * @param unknown $appId            
     * @return mixed $token
     */
    public function makeToken($uid, $appId)
    {
        $tokenCache = $this->getTokenCache($uid);
        if (! $tokenCache) {
            return $tokenCache;
        }
        $tokenCache['appId'] = $appId;
        $token = Crypt::encrypt(implode('|', $tokenCache), $this->tokenSalt);
        return $token;
    }

    /**
     * 检查token
     * 
     * @param unknown $token            
     * @return mixed $token 整数为错误代码，否则为token数组
     */
    public function checkToken($token)
    {
        $token = Crypt::decrypt($token, $this->tokenSalt);
        if (! $token) {
            $this->setErrorCode(self::ERR_TOKEN_INVALID);
            return false;
        }
        list ($uid, $password, $loginTime, $appId) = $token = explode('|', $token);
        // 获取服务器记载的token缓存
        $tokenCache = $this->getTokenCache($uid);
        if (! $tokenCache) {
            return $tokenCache;
        }
        if ($tokenCache['loginTime'] != $loginTime) {
            $this->setErrorCode(self::ERR_TOKEN_RELOGIN);
            return false;
        }
        if ($tokenCache['password'] != $password) {
            $this->setErrorCode(self::ERR_PASSWORD_CHANGE);
            return false;
        }
        $token = array('uid' => $uid,'password' => $password,'loginTime' => $loginTime,'appId' => $appId);
        return $token;
    }

    /**
     * 缓存Token信息
     * 
     * @param unknown $user            
     * @return bool $rs
     */
    public function setTokenCache($user, $reset = false)
    {
        $key = $this->getTokenKey($user['id']);
        $token = array('uid' => $user['id'],'password' => md5($user['password']),'loginTime' => $user['loginTime'],'start' => time());
        $token['start'] = ($serverToken = $this->cache->get($key)) === false || $reset ? time() : $serverToken['start'];
        $rs = $this->cache->set($key, $token, $this->tokenMinExpire);
        return $rs;
    }

    /**
     * 删除TokenCache
     * 
     * @param unknown $token            
     * @return bool $rs
     */
    public function delTokenCache($token)
    {
        // 如果是数字则认为是uid
        if (is_int($token)) {
            $uid = $token;
        } else {
            $token = $this->checkToken($token);
            if (! is_array($token)) {
                return $token;
            }
            $uid = $token['uid'];
        }
        $rs = $this->cache->del($this->getTokenKey($uid));
        if ($rs) {
            return $rs;
        } else {
            $this->setErrorCode(self::ERR_CACHE_DEL);
            return false;
        }
    }

    /**
     * 获取token存储在cache中的key
     * 
     * @param unknown $uid            
     * @return string
     */
    private function getTokenKey($uid)
    {
        return sprintf($this->tokenKey, $uid);
    }

    /**
     * 获取Token信息
     * 可能出现的错误：超过最小生存时间、超过最大生存时间
     * 
     * @param unknown $uid            
     * @return mixed $tokenCache
     */
    public function getTokenCache($uid)
    {
        $tokenCache = $this->cache->get($this->getTokenKey($uid));
        if ($tokenCache === false) {
            $this->setErrorCode(self::ERR_TOKEN_EXCEED_MIN);
            return false;
        }
        if ($tokenCache['start'] + $this->tokenMaxExpire < time()) {
            $this->setErrorCode(self::ERR_TOKEN_EXCEED_MAX);
            return false;
        }
        unset($tokenCache['start']);
        return $tokenCache;
    }
    
}