<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc Base64 加密实现类
 */
namespace orc\library;

class Crypt
{

    /**
     * 加密
     *
     * @param string $defaultKey
     *            关键密钥
     * @param string $str
     *            需加密的字串
     * @param int $expiry            
     * @return string 加密后的加密字串
     */
    public function encrypt($str, $key, $expiry = 0)
    {
        return $this->cryptCode($str, "encode", $expiry);
    }

    /**
     * 解密
     *
     * @param string $defaultKey
     *            关键密钥
     * @param string $str
     *            需解密的字串
     * @param int $expiry            
     * @return string 解密后的字串
     */
    public function decrypt($str, $key)
    {
        return $this->cryptCode($str, "decode");
    }

    /**
     * 加密解密
     *
     * @param string $str
     *            加密串
     * @param string $operation
     *            操作类型（机密或解密） 加密encode , 解密decode
     * @param int $expiry            
     * @param string $defaultKey
     *            关键密钥
     * @return string 加密或解密串
     */
    private function cryptCode($str, $operation = "decode", $expiry = 0, $key = '')
    {
        $ckeyLength = 4;
        $key = md5($key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckeyLength ? ($operation == 'decode' ? substr($str, 0, $ckeyLength) : substr(md5(microtime()), - $ckeyLength)) : '';
        
        $cryptkey = $keya . md5($keya . $keyc);
        $keyLength = strlen($cryptkey);
        
        $str = $operation == 'decode' ? base64_decode(substr($str, $ckeyLength)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($str . $keyb), 0, 16) . $str;
        
        $strLength = strlen($str);
        
        $result = '';
        $box = range(0, 255);
        
        $rndkey = array();
        for ($i = 0; $i <= 255; $i ++) {
            $rndkey[$i] = ord($cryptkey[$i % $keyLength]);
        }
        
        for ($j = $i = 0; $i < 256; $i ++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        
        for ($a = $j = $i = 0; $i < $strLength; $i ++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($str[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        
        if ($operation == 'decode') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return false;
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }
}