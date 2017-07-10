<?php
/**
 * @author lpywy728394@163.com
 * @date 2017-05-25
 * @desc 树形
 */
namespace orc\library;

class Tree
{
    
    protected $fields = [
        'idField' => 'id',
        'parentField' => 'parent',
        'childField' => 'child',
        'levelField' => 'level',
        'isLeafField' => 'is_leaf'
    ];

    public function __construct($option = [])
    {
        $this->fields = array_merge($this->fields, array_filter($option));
    }

    public function getTree($data, $parentId = 0, $level = 1)
    {
        $tree = [];
        foreach ($data as $v) {
            if ($v[$this->fields['parentField']] == $parentId) {
                $id = $v[$this->fields['idField']];
                $v[$this->fields['levelField']] = $level;
                $child = $this->getTree($data, $id, $level + 1);
                if ($child) {
                    $v[$this->fields['childField']] = $child;
                }
                $tree[] = $v;
            }
        }
        return $tree;
    }

    public function treeSelect($data)
    {
        $data = $this->getTree($data);
        $optionStr = $this->buildOptions($data);
        $str = "<select>{$optionStr}</select>";
        return $str;
    }

    public function buildOptions($data, $deep = 0)
    {
        $str = $blank = '';
        for ($i = 1; $i < $deep; $i ++) {
            $blank .= '&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $j = 1;
        foreach ($data as $v) {
            $name = $v['name'];
            if ($v[$this->fields['parentField']] != 0) {
                $name = $blank . ($j == count($data) ? '└' : '├') . '&nbsp;' . $name;
            }
            $str .= '<option value="' . $v['id'] . '">' . $name . '</option>';
            if (! empty($v[$this->fields['childField']])) {
                $str .= $this->buildOptions($v[$this->fields['childField']], $deep + 1);
            }
            $j ++;
        }
        return $str;
    }

    public function getList($data, $parentId = 0)
    {
        $treeList = [];
        foreach ($data as $v) {
            if ($v[$this->fields['parentField']] == $parentId) {
                $childList = $this->getList($data, $v[$this->fields['idField']]);
                if ($this->fields['isLeafField']) {
                    $v[$this->fields['isLeafField']] = empty($childList);
                }
                $treeList = array_merge($treeList, array(
                    $v
                ), $childList);
            }
        }
        return $treeList;
    }
}