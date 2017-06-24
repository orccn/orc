<?php
namespace Controller;

use model\FieldModel;
class FieldController extends AdminBase
{

    function ls()
    {
        $fieldList = FieldModel::ins()->getFieldList(intval(I('door_code')));
        $this->success($fieldList);
    }

    function edit()
    {
        $this->responseValidate([
            'field_en:字段英文名称' => 'maxLen:100',
            'field_zh:字段中文名称' => 'maxLen:100',
            'door_code:功能编码' => ['exists:MenuModel,door_code'],
        ]);
        $arr = [];
        $arr['field_en'] = strtolower(I('field_en'));
        $arr['field_zh'] = I('field_zh');
        $arr['door_code'] = intval(I('door_code'));
        //修改
        if ($fieldCode = intval(I('field_code'))){
            FieldModel::ins()->update($arr,['field_code'=>$fieldCode]);
        }else{
            FieldModel::ins()->insert($arr);
        }
        $this->success();
    }
    
    function del()
    {
        $rs = FieldModel::ins()->delete(intval(I('field_code')));
        $this->success();
    }
    
    function sort()
    {
        $codes = array_map(function ($v) {
            return intval($v);
        }, explode(',', I('field_codes')));
        $codes = array_filter($codes);
        if (empty($codes)){
            $this->success();
        }
        $fieldMap = array_column(FieldModel::ins()->where(['door_code'=>intval(I('door_code'))])->select(), 'rank', 'field_code');
        $i = 0;
        foreach ($codes as $code){
            $i++;
            if (isset($fieldMap[$code]) && $fieldMap[$code]==$i){
                continue;
            }
            FieldModel::single()->update(['rank'=>$i],$code);
        }
        $this->success();
    }
}




















