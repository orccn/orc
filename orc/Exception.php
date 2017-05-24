<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 异常基类
 */
namespace orc;

class Exception extends \Exception
{

    public function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
