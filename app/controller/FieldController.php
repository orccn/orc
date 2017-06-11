<?php
namespace Controller;

use model\FieldModel;
class FieldController extends AdminBase
{

    function ls()
    {
        $fieldList = FieldModel::ins()->where([
            'door_code' => I('door_code')
        ])->order('rank')->select();
        $this->success($fieldList);
    }

    function edit()
    {
        $this->responseValidate([
            'field_enname:字段英文名称' => 'maxLen:100',
            'field_zhname:字段中文名称' => 'maxLen:100',
            'door_code:功能编码' => ['exists:FieldModel,door_code'],
        ]);
        $arr = [];
        $arr['field_enname'] = I('field_enname');
        $arr['field_zhname'] = I('field_zhname');
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
}




















