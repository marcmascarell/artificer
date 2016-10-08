<?php

return [

    /*
     * Keys are useless
     */

    'dashboard' => [
        'route'  => 'admin.home',
        'title' => 'Dashboard',
        'icon'  => '<i class="fa fa-dashboard"></i>',
        'permissions' => [
            'admin',
        ],
    ],

    'extensions'   => [
        'route'  => 'admin.extensions',
        'title' => 'Extensions',
        'icon'  => '<i class="fa fa-plug"></i>',
        'permissions' => [
            'admin',
            'user',
        ],
    ],

];
