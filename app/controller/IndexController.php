<?php
namespace Controller;

use orc\Controller;

class IndexController extends Controller
{
    function index()
    {
        return $this->fetch();
    }
}