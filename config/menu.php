<?php

return array(

	'dashboard' => array(
		'link'  => route('admin.home'),
		'title' => 'Dashboard',
		'icon'  => '<i class="fa fa-dashboard"></i>',
        'permissions' => array('admin')
	),

	'plugins'   => array(
		'link'  => route('admin.page.plugins'),
		'title' => 'Plugins',
		'icon'  => '<i class="fa fa-plug"></i>',
        'permissions' => array('admin', 'user')
	),

);