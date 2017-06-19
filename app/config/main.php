<?php
return [
    'loginWhiteList' => [
        'self/login',
        'self/logout',
    ],
    'portletClass' => 'portlet light',
    'titleAddClass'=> 'title-add btn btn-sm green',
    'tdAddClass' => 'td-add btn btn-xs green btn-outline',
    'tdDetailClass' => 'td-detail btn btn-xs yellow btn-outline',
    'tdSaveClass' => 'td-save btn btn-xs blue btn-outline',
    'tdDelClass' => 'td-del btn btn-xs red btn-outline',
    'roles' => [
        1 => '超级用户',
        2 => '管理者',
        3 => '临床用户'
    ]
];