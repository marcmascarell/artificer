<?php

return [

    /*
     * Specify where models live
     */
    'directories' => [
        // '\\App\\Models' => app_path() . '/Models',
    ],

    /*
     * You can also specify models individually
     */
    'models' => [
        \App\User::class,
    ],

    /*
     * Models that will not be shown in menu (Example: BookAttribute)
     *
     * Useful when you are using directory model scan.
     */
    'hidden' => [],

    'route_permission' => [
        'admin.model.store'   => 'create',
        'admin.model.create'  => 'create',
        'admin.model.update'  => 'update',
        'admin.model.edit'    => 'update',
        'admin.model.destroy' => 'delete',
        'admin.model.show'    => 'view',
        'admin.model.all'     => 'view',
    ],

    /*
     * All model configs will use this defaults (Merging)
     */
    'default' => [
        // The real value will never be shown (just that)
        'hidden' => ['password'],

        // Model's 'fillable' property. Fallback to the Model if empty []
        'fillable' => ['*'],

        // Model's 'guarded' property. Fallback to the Model if empty []
        'guarded' => ['id'],

        // Fields that are shown on creation
        'create' => [
            'visible' => ['*'],
            'hidden' => ['id'],
        ],

        // Fields that are shown on edit
        'edit' => [
            'visible' => ['*'],
            'hidden' => ['id'],
        ],

        // Fields that will be shown when on list view mode
        'list' => [
            'visible' => ['*'],
            'hidden' => ['password'],
        ],

        // Fields that will be shown when on detailed item view mode
        'show' => [
            'visible' => ['*'],
        ],

        'fields' => [],

        // Attributes of fields, applied unless field has attributes
        'attributes' => [
            'class' => 'form-control',
        ],
    ],
];
