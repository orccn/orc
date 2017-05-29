<?php
/**
 * @author lpywy728394@163.com
 * @date 2017-05-29
 * @desc 树
 */
namespace library;

class Tree extends \orc\library\Tree
{
    public function __construct($option = [])
    {
        $this->fields['textField'] = 'text';
        $this->fields = array_merge($this->fields, array_filter($option));
    }
    
    public function getJSTreeData($data, $parentId = '00', $level = 1)
    {
        $tree = [];
        foreach ($data as $v) {
            if ($v[$this->fields['parentField']] == $parentId) {
                $id = $v[$this->fields['idField']];
                $item = [
                    'id' => $id,
                    'text' => $v[$this->fields['textField']],
                ];
                $child = $this->getJSTreeData($data, $id, $level + 1);
                if ($child) {
                    $item['children'] = $child;
                }
                $tree[$id] = $item;
            }
        }
        return $tree;
    }
}