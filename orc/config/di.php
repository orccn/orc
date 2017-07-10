<?php
return [
    'set' => [
        'router'    => 'orc\Router',
        'validator' => 'orc\library\Validator',
        'tree'      => 'orc\library\Tree',
        'dependency'=> 'orc\library\Dependency',
        'crypt'     => 'orc\library\Crypt'
    ],
    'singletons' => [
        'lang'  => 'orc\Lang',
        'hook'  => 'orc\Hook',
        'view'  => 'orc\View',
        'res'   => 'orc\Response',
        'url'   => 'orc\URL'
    ]
];