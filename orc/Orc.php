<?php
/**
 * @author lpywy728394@163.com
 * @date 2016-05-25
 * @desc 框架核心类
 */
namespace orc;

require_once 'Functions.php';
require_once 'Loader.php';

// 环境
define('ENV_DEV', 'dev');
define('ENV_TEST', 'test');
define('ENV_PRO', 'pro');
defined('ENV') or define('ENV', ENV_DEV);

// 框架目录、app及其子目录
define('DS', '/');
define('BASE_DIR', str_replace('\\', DS, __DIR__ . DS));
defined('APP_DIR') or define('APP_DIR', dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . DS);
define('CONTROLLER_NAMESPACE', 'Controller');
define('VIEW_DIR', APP_DIR . 'view' . DS);
define('CONFIG_DIR', APP_DIR . 'config' . DS);

// 请求方式
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ? true : false);
define('REQUEST_METHOD', isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get');

// 自动加载类
Loader::rigist([
    dirname(BASE_DIR) . DS,
    APP_DIR
]);

// 加载配置文件路径
Config::addPath([
    CONFIG_DIR,
    CONFIG_DIR . ENV . DS
]);

//预加载配置文件
if (is_array(config('preload'))){
    foreach (config('preload') as $file) {
        Config::load($file);
    }
}

// 加载语言配置路径
Lang::addPath(APP_DIR . 'lang');

// 注册所有插件
if (config('plugin.')) {
    foreach (config('plugin.') as $tag => $plugins) {
        Hook::set($tag, $plugins);
    }
}

// URL路由、请求分发
$pathInfo = parse_url(URL::getCurrentURL(), PHP_URL_PATH);
Response::output(Router::ins($pathInfo)->dispatch());




