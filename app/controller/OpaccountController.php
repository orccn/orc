<?php
namespace Controller;

use library\Comm;
use orc\Response;
use model\OpaccountModel;
use library\DataTable;
class OpaccountController extends AdminBase
{

    function index()
    {
        $fieldList = DataTable::checkFieldList('opaccount/index');
        if (Comm::isAjax()){
            $order = DataTable::getOrder(array_column($fieldList, 'field_en'));
            $accList = OpaccountModel::single()->order($order)->datatable();
            Response::exitJson($accList);
        }else{
            $this->assign('tableHeader',DataTable::getHeader($fieldList));
            $this->assign('tableColumns',DataTable::getColumns($fieldList));
            $this->showFrame();
        }
    }
}




















