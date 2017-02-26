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
        Collective\Html\HtmlServiceProvider::class,
        Stolz\Assets\Laravel\ServiceProvider::class,
        Laracasts\Flash\FlashServiceProvider::class,
        Spatie\Permission\PermissionServiceProvider::class,
        Mascame\Artificer\DefaultThemeServiceProvider::class,
        Mascame\Artificer\Providers\InstallServiceProvider::class,
        Mascame\Artificer\Providers\HooksServiceProvider::class,

        // Extensions
        Mascame\Artificer\LoginPluginServiceProvider::class,
        Mascame\Artificer\ArtificerWidgetsServiceProvider::class,
        Mascame\Artificer\LogReaderPluginServiceProvider::class,
    ],

    'aliases' => [
        'HTML' => \Collective\Html\HtmlFacade::class,
        'Form' => \Collective\Html\FormFacade::class,
        'Assets' => \Stolz\Assets\Laravel\Facade::class,
    ],

    'commands' => [
        Mascame\Artificer\Commands\ModalConfigGenerator::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Extension driver
    |--------------------------------------------------------------------------
    |
    | System used to store the extension status.
    | Keep in mind that existent extensions in your system are already
    | pulled in, but have an installed/uninstalled status.
    |
    | Supported: "database"
    |
    */

    'extension_driver' => 'database',

    'extension_drivers' => [

        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'table' => 'artificer_extensions',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Artificer's Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'artificer_migrations',

    /*
    |--------------------------------------------------------------------------
    | Vendor folder (Where your app dependencies live)
    |--------------------------------------------------------------------------
    |
    | The folder is normally created automatically by Composer.
    |
    */

    'vendor_path' => base_path('vendor'),

    /*
    |--------------------------------------------------------------------------
    | Icons used by the app
    |--------------------------------------------------------------------------
    */

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
        'extension' => 'fa fa-plug',
    ],
];
