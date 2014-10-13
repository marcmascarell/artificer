<?php

return array(

	'directories'      => array(
		app_path() . '/models'
	),

	'hidden'           => array(
		'BookAttribute'
	),

	'route_permission' => array(
		'admin.store'   => 'create',
		'admin.create'  => 'create',
		'admin.update'  => 'update',
		'admin.edit'    => 'update',
		'admin.destroy' => 'delete',
		'admin.show'    => 'view',
		'admin.all'     => 'view',
	),

);