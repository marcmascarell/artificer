<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models discovery
    |--------------------------------------------------------------------------
    |
    | Define how Artificer should find your models or specify them one by one.
    |
    */

    'directories' => [
        // '\\App\\Models' => app_path() . '/Models',
    ],

    'models' => [
        // \App\User::class,
        \App\User::class,
        \App\Test::class,
        \Mascame\Artificer\ArtificerUser::class,
        \Mascame\Artificer\Model\Permission\Role::class,
        \Mascame\Artificer\Model\Permission\Permission::class,
    ],

    'hidden' => [
        // \App\User::class,
    ],

    // All model configs will use this defaults (Merging)
    'default' => [

        /*
        |--------------------------------------------------------------------------
        | Mass Assignment
        |--------------------------------------------------------------------------
        |
        | A mass-assignment vulnerability occurs when a user passes an unexpected HTTP
        | parameter through a request, and that parameter changes a column in your database
        | you did not expect.
        |
        | This fields are fallback to their correspondent Model property if empty.
        |
        */

        'fillable' => ['*'],

        'guarded' => ['id'],

        /*
        |--------------------------------------------------------------------------
        | Field visibility
        |--------------------------------------------------------------------------
        |
        | Fields that will be hidden or shown on a certain action.
        |
        */

        'browse' => [
            'visible' => ['*'],
            'hidden' => [
                'password',
                'created_at',
                'updated_at',
                'remember_token',
            ],
        ],

        'read' => [
            'visible' => ['*'],
        ],

        'edit' => [
            'visible' => ['*'],
            'hidden' => ['id'],
        ],

        'add' => [
            'visible' => ['*'],
            'hidden' => ['id'],
        ],

        /*
        |--------------------------------------------------------------------------
        | Fields
        |--------------------------------------------------------------------------
        |
        */

        'fields' => [],

        // Todo: does this apply anymore?
        // Attributes of fields, applied unless field has attributes
        'attributes' => [
            'class' => 'form-control',
        ],
    ],
];
