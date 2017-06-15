<?php
// phpinfo();
// exit;
session_start();
$_SESSION['role'] = 2;
error_reporting(E_ALL);
header('Content-type:text/html;charset=utf8');
require '../../orc/Orc.php';
