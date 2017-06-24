<?php
return [
    'whiteList' => [
        'self/init_password',
        'self/update_password',
    ],
    'sysMenu' => [
        'menu/edit',
        'menu/del',
        'menu/detail',
        'feild/edit',
        'feild/del',
        'feild/ls',
        'feild/sort',
    ],
    'dependency' => [
        'user/index' => ['unit/tree'],
    ],
];