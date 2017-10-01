<?php

return [

    [
        'title' => 'Dashboard',
        'route'  => 'admin.home',
        'icon'  => '<i class="fa fa-dashboard"></i>',
        'permissions' => [
            'admin',
        ],
    ],
    [
        'title' => 'Extensions',
        'route'  => 'admin.extensions',
        'icon'  => '<i class="fa fa-plug"></i>',
        'permissions' => [
            'admin',
            'user',
        ],
    ],

];
