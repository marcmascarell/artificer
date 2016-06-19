<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Autoloaded Service Providers
    |--------------------------------------------------------------------------
    */
	'providers' => [
        \Collective\Html\HtmlServiceProvider::class,
        \Mascame\Artificer\DefaultThemeServiceProvider::class
	],

    'aliases' => [
        'HTML' => \Collective\Html\HtmlFacade::class,
        'Form' => \Collective\Html\FormFacade::class,
    ]
];