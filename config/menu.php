<?php

return array(

	'dashboard' => array(
		'link'  => URL::route('admin.home'),
		'title' => 'Dashboard',
		'icon'  => '<i class="fa fa-dashboard"></i>',
        'permissions' => array('admin')
	),

	'plugins'   => array(
		'link'  => URL::route('admin.page.plugins'),
		'title' => 'Plugins',
		'icon'  => '<i class="fa fa-plug"></i>',
        'permissions' => array('admin', 'user')
	),

);