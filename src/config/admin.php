<?php

return [

    'title'        => 'Artificer',

//	'default_route' => route('admin.model.all', array('slug' => 'user'), $absolute = true),

    'theme'        => 'artificer-default-theme',

    'list'         => [
        'hidden'     => [
            'image_center',
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

//	'routes' => Config::get('artificer::routes'),

    'menu'         => Config::get('artificer::menu'),

    'plugins'      => Config::get('artificer::plugins'),

    'thumbnails'   => Config::get('artificer::thumbnails'),

    'types'        => Config::get('artificer::fields.types'),

    'classmap'     => Config::get('artificer::fields.classmap'),

    'models'       => Config::get('artificer::models'),

    'auth'         => Config::get('artificer::auth'),

    'localization' => Config::get('artificer::localization'),
];
