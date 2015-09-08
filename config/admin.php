<?php

return array(

	'title'        => 'Artificer',

//	'default_route' => route('admin.model.all', array('slug' => 'user'), $absolute = true),

	'theme'        => 'artificer-default-theme',

	'list'         => [
		'hidden'     => [
			'image_center'
		],
		'showable'   => [],
		'pagination' => 5,
	],

	'edit'         => [
		'hidden'   => [],
		'showable' => [],
	],

    'icons' => [
	    'edit' => 'fa fa-pencil',
	    'filter' => 'fa fa-filter',
	    'new' => 'fa fa-plus',
	    'search' => 'fa fa-search',
	    'dashboard' => 'fa fa-dashboard',
	    'models' => 'fa fa-th',
	    'info' => 'fa fa-info',
	    'save' => 'fa fa-save',
	    'show' => 'fa fa-eye',
	    'delete' => 'fa fa-remove',
	    'sort-up' => 'fa fa-long-arrow-up',
	    'sort-down' => 'fa fa-long-arrow-down',
    ],


//	'routes' => config('artificer.routes'),

	'menu'         => config('artificer.menu'),

	'plugins'      => config('artificer.plugins'),

	'thumbnails'   => config('artificer.thumbnails'),

	'types'        => config('artificer.fields.types'),

	'classmap'     => config('artificer.fields.classmap'),

	'models'       => config('artificer.models'),

	'auth'         => config('artificer.auth'),

	'localization' => config('artificer.localization'),
);