<?php

return array(

	'directories'      => array(
		app_path() . '/models'
	),

	'hidden'           => array(
		'BookAttribute'
	),

	'route_permission' => array(
		'admin.model.store'   => 'create',
		'admin.model.create'  => 'create',
		'admin.model.update'  => 'update',
		'admin.model.edit'    => 'update',
		'admin.model.destroy' => 'delete',
		'admin.model.show'    => 'view',
		'admin.model.all'     => 'view',
	),

);