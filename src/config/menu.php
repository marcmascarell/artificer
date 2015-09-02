<?php

return array(

    'dashboard' => array(
        'route' => 'admin.home',
        'title' => 'Dashboard',
        'icon' => '<i class="fa fa-dashboard"></i>',
        'permissions' => array('admin')
    ),
    'plugins' => array(
        'route' => 'admin.page.plugins',
        'title' => 'Plugins',
        'icon' => '<i class="fa fa-plug"></i>',
        'permissions' => array('admin', 'user')
    ),

);