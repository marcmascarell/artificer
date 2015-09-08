<?php

return array(

	'dashboard' => [
		'link'  => 'admin.home',
		'title' => 'Dashboard',
		'icon'  => '<i class="fa fa-dashboard"></i>',
		'permissions' => [
			'admin'
		]
	],

	'plugins'   => [
		'link'  => 'admin.page.plugins',
		'title' => 'Plugins',
		'icon'  => '<i class="fa fa-plug"></i>',
		'permissions' => [
			'admin',
			'user'
		]
	],

);