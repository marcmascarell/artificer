<?php

return [

    'dashboard' => [
        'link'  => URL::route('admin.home'),
        'title' => 'Dashboard',
        'icon'  => '<i class="fa fa-dashboard"></i>',
        'permissions' => ['admin'],
    ],

    'plugins'   => [
        'link'  => URL::route('admin.page.plugins'),
        'title' => 'Plugins',
        'icon'  => '<i class="fa fa-plug"></i>',
        'permissions' => ['admin', 'user'],
    ],

];
