<?php
/**
 * @author lpywy728394@163.com
 * @date 2017-06-24
 */
namespace library;

use orc\Response;
use model\FieldModel;

class DataTable
{
    public static function checkFieldList($url)
    {
        $fieldList = FieldModel::single()->getFieldListByUrl('opaccount/index');
        if ($fieldList===false){
            Response::error('该模块未开启字段控制功能');
        }
        if (empty($fieldList)){
            Response::error('该模块未添加控制字段');
        }
        return $fieldList;
    }
    
    public static function getOrder($fields)
    {
        if (empty($fields)){
            Response::error("没有可允许的排序字段");
        }
        $columns = I('columns');
        $order = I('order');
        if (!isset($columns[$order[0]['column']]['data'])){
            Response::error("前端排序字段传递错误");
        }
        $orderField = $columns[$order[0]['column']]['data'];
        if (!in_array($orderField, $fields)){
            Response::error("排序字段不存在");
        }
        $orderType = $order[0]['dir'] == 'asc' ? 'asc' : 'desc';
        return "$orderField $orderType";
    }
    
    public static function getHeader($fieldList)
    {
        $str = '';
        foreach ($fieldList as $v){
            $str .= "<th>{$v['field_zh']}</th>";
        }
        return "<tr>$str</tr>";
    }
    
    public static function getColumns($fieldList)
    {
        $arr = [];
        foreach ($fieldList as $v){
            $arr[] = ['data'=>$v['field_en']];
        }
        return json_encode($arr);
    }
    
}