<?php

return array(

	'title'        => 'Artificer',

//	'default_route' => route('admin.all', array('slug' => 'user'), $absolute = true),

	'theme'        => 'artificer::themes.admin-lte-custom',

	'list'         => array(
		'hidden'     => array(
			'image_center'
		),
		'showable'   => array(),
		'pagination' => 5,
	),

	'edit'         => array(
		'hidden'   => array(),
		'showable' => array(),
	),

//	'routes' => Config::get('artificer::routes'),

	'menu'         => Config::get('artificer::menu'),

	'plugins'      => Config::get('artificer::plugins'),

	'thumbnails'   => Config::get('artificer::thumbnails'),

	'types'        => Config::get('artificer::fields.types'),

	'classmap'     => Config::get('artificer::fields.classmap'),

	'models'       => Config::get('artificer::models'),

	'auth'         => Config::get('artificer::auth'),

	'localization' => Config::get('artificer::localization'),
);