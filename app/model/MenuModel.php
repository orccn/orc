<?php
namespace model;

use orc\Model;
use library\Tree;

class MenuModel extends Model
{

    protected $tableName = 'door_dict';
    
    protected $suburl = '';

    public function __construct($tableName = '', $dbKey = '')
    {
        parent::__construct($tableName, $dbKey);
        $this->suburl = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
        $this->where(['is_delete'=>0]);
    }
    
    public function delete($code)
    {
        return $this->where(['door_code'=>$code])->update(['is_delete'=>1]);
    }
    
    public function getMenuTree()
    {
        $option = [
            'idField' => 'door_code',
            'parentField' => 'door_parent'
        ];
        return Tree::ins($option)->getTree($this->where(['is_menu'=>1])->select(), 0);
    }
    
    public function getMenuHtml($data)
    {
        $html = '';
        foreach($data as $v){
            
            $arrow = empty($v['child']) ?  '' : '<span class="arrow"></span>';
            $childHtml = $arrow ? '<ul class="sub-menu">'.$this->getMenuHtml($v['child']).'</ul>' : '';
            $href = empty($v['door_url']) ? 'javascript:;' : '/'.ltrim($v['door_url'],'/');
            $selected = $openClass = '';
            if ($v['door_url'] == $this->suburl){
                $selected = '<span class="selected"></span>';
                $openClass = ' active open ';
            }
            
            $html .= '<li class="nav-item '.$openClass.'"><a href="'.$href.'" class="nav-link nav-toggle">';
            $html .= '<i class="icon-layers"></i><span class="title">'.$v['door_name'].'</span>';
            $html .= $selected.$arrow.'</a>'.$childHtml.'</li>';
        }
        return $html;
    }
}