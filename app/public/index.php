<?php
// phpinfo();
// exit;
session_start();
$_SESSION['user']['role'] = 2;
$_SESSION['user']['name'] = '张三';
error_reporting(E_ALL);
header('Content-type:text/html;charset=utf8');
require '../../orc/Orc.php';
