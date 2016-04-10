<?php

return [

	'dashboard' => [
		'route'  => 'admin.home',
		'title' => 'Dashboard',
		'icon'  => '<i class="fa fa-dashboard"></i>',
		'permissions' => [
			'admin'
		]
	],

	'plugins'   => [
		'route'  => 'admin.page.plugins',
		'title' => 'Plugins',
		'icon'  => '<i class="fa fa-plug"></i>',
		'permissions' => [
			'admin',
			'user'
		]
	],

	'widgets'   => [
		'route'  => 'admin.widgets',
		'title' => 'Widgets',
		'icon'  => '<i class="fa fa-cubes"></i>',
		'permissions' => [
			'admin',
			'user'
		]
	],

];