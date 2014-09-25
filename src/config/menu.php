<?php

return array(

	'dashboard' => array(
		'link'  => URL::route('admin.home'),
		'title' => 'Dashboard',
		'icon'  => '<i class="fa fa-dashboard"></i>',
		'user_access' => 'admin'
	),

	'plugins'   => array(
		'link'  => URL::route('admin.page.plugins'),
		'title' => 'Plugins',
		'icon'  => '<i class="fa fa-plug"></i>',
		'user_access' => 'admin'
	),

);