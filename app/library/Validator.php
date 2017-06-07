<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 验证类
 */
namespace library;

class Validator extends \orc\library\Validator
{

    public function __construct($data, $name = '', $rules = [])
    {
        parent::__construct($data, $name, $rules);
        $this->setError([
            'exists' => '%s记录不存在',
            'notExists' => '%s记录已存在'
        ]);
    }

    public function exists($model, $pk)
    {
        return $this->routine(__FUNCTION__, func_get_args(), function ($model, $pk) {
            $model = 'model\\' . $model;
            $count = $model::single()->where([
                $pk => $this->data
            ])->count();
            return $count > 0;
        });
    }

    public function notExists($model, $pk)
    {
        return $this->routine(__FUNCTION__, func_get_args(), function ($model, $pk) {
            $model = 'model\\' . $model;
            $count = $model::single()->where([
                $pk => $this->data
            ])->count();
            return $count == 0;
        });
    }
}