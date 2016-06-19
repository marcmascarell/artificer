<?php

return [

	'title' => 'Artificer',

//	'default_route' => route('admin.model.all', array('slug' => 'user'), $absolute = true),

	'route_prefix' => 'admin',

	'theme' => 'artificer-default-theme',

    'icons' => [
	    'edit' => 'fa fa-pencil',
		'dashboard' => 'fa fa-dashboard',
		'delete' => 'fa fa-remove',
		'filter' => 'fa fa-filter',
		'info' => 'fa fa-info',
		'models' => 'fa fa-th',
		'new' => 'fa fa-plus',
		'save' => 'fa fa-save',
		'search' => 'fa fa-search',
		'show' => 'fa fa-eye',
		'sort-up' => 'fa fa-long-arrow-up',
	    'sort-down' => 'fa fa-long-arrow-down',
    ],

	'auth' => config('artificer.auth'),
	'classmap' => config('artificer.fields.classmap'),
	'localization' => config('artificer.localization'),
	'menu' => config('artificer.menu'),
	'models' => config('artificer.models'),
	'plugins' => config('artificer.plugins'),
	'providers' => config('artificer.providers'),
	'types' => config('artificer.fields.types')
	
];