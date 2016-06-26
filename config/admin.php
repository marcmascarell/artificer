<?php

return [

	'title' => 'Artificer',
	
	'route_prefix' => 'admin',

	'theme' => 'artificer-default-theme',

	/*
    |--------------------------------------------------------------------------
    | Providers, aliases & commands will be conveniently lazy loaded
    |--------------------------------------------------------------------------
    */
	
	'providers' => [
		\Collective\Html\HtmlServiceProvider::class,
		\Mascame\Artificer\DefaultThemeServiceProvider::class,
		\Mascame\Artificer\ArtificerWidgetsServiceProvider::class,
	],

	'aliases' => [
		'HTML' => \Collective\Html\HtmlFacade::class,
		'Form' => \Collective\Html\FormFacade::class,
	],

	'commands' => [
		\Mascame\Artificer\Commands\ModalConfigGenerator::class
	],
	
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
    ]
];