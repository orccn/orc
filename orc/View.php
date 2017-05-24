<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 视图
 */
namespace orc;

class View
{
    use traits\Instance;

    protected $vars = [];

    /**
     * 模板变量赋值
     *
     * @access public
     * @param mixed $name            
     * @param mixed $value            
     */
    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->vars = array_merge($this->vars, $name);
        } else {
            $this->vars[$name] = $value;
        }
        return $this;
    }

    /**
     * 取得模板变量的值
     *
     * @access public
     * @param string $name            
     * @return mixed
     */
    public function get($name = '')
    {
        if (! $name) {
            return $this->vars;
        }
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }

    /**
     * 模板
     *
     * @param string $file            
     */
    public function render($file)
    {
        @list ($file, $ext) = explode('.', $file);
        if (! $ext) {
            $ext = 'php';
        }
        $file .= '.' . $ext;
        // TODO 判断模板是否存在
        ob_start();
        extract($this->vars, EXTR_OVERWRITE);
        include VIEW_DIR . $file;
        $content = ob_get_clean();
        return $content;
    }

    /**
     * json及jsonp返回
     *
     * @param string $cb            
     * @param string $jsonpHandler            
     * @return string
     */
    public function json($cb = null, $jsonpHandler = '')
    {
        $vars = $this->vars ? $this->vars : null;
        if ($cb) {
            $vars = $cb($vars);
        }
        $json = json_encode($vars);
        if ($jsonpHandler) {
            $jsonpHandler && $json = $jsonpHandler . "(" . $json . ");";
        }
        return $json;
    }
}