<?php
namespace plugin;

class AdminPlugin
{

    public function ctlInsBefore()
    {
        define('URL_PATH_TRIM', ltrim(URL_PAHT, '/'));
    }
}