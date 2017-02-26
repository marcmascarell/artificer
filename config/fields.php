<?php

use \Mascame\Artificer\Fields\Types as ArtificerTypes;

return [

    'classmap' => [
//        'bool' => Types\Checkbox::class,
//        'boolean' => Types\Checkbox::class,
//		'image'   => \Mascame\Artificer\Plugins\Plupload\PluploadField::class,
        'hasOne'  => ArtificerTypes\Relations\hasOne::class,
        'hasMany' => ArtificerTypes\Relations\hasMany::class,
        'belongsTo' => ArtificerTypes\Relations\belongsTo::class,
    ],

    /*
     * field_type => [options]
     */
    'types' => [

        // Default type that will be used in case of no better match
        'default' => [
            'type' => 'text',
        ],

        'checkbox' => [
            'autodetect' => [
                'accept',
                'active',
                'boolean',
                'activated',
                'confirmed',
            ],
        ],

        'custom' => [],

        'email' => [],

        'datetime' => [

            'regex' => [
                '/_at$/',
                '/_on$/',
            ],

            'attributes' => [
                'class' => 'form-control',
            ],

            'widgets' => [
                \Mascame\Artificer\Widgets\DateTimepicker::class,
            ],

        ],

        'date' => [
            'autodetect' => [
                '_at',
            ],

            'attributes' => [
                'class' => 'form-control',
            ],

            'widgets' => [
                \Mascame\Artificer\Widgets\DateTimepicker::class,
            ],
        ],

        'file' => [],

        'hasOne' => [
            'autodetect' => [
                '_id',
                'user_id',
                'fake_id',
            ],
        ],

        'hasMany' => [],

        'image' => [
            'autodetect' => [
                'image',
            ],
        ],

        'ip' => [
            'regex' => [
                '/_ip$/',
            ],
        ],

        'key' => [
            'autodetect' => [
                'id',
            ],
        ],

        'link' => [
            'autodetect' => [
                'url',
            ],
        ],

        'password' => [
            'autodetect' => [
                'password',
            ],
        ],

        'published' => [],

        'radio' => [
            'autodetect' => [
                'option',
                'selection',
            ],
        ],

        'text' => [
            'autodetect' => [
                'title',
                'username',
                'name',
            ],
        ],

        'textarea' => [
            'autodetect' => [
                'description',
            ],
        ],

        // Todo
//        'select' => [
//            'autodetect' => [
//                'city',
//                'country',
//            ],
//
//            'widgets' => [
//                \Mascame\Artificer\Widgets\Chosen::class,
//            ]
//        ],

        'wysiwyg' => [
            'autodetect' => [
                'body',
                'text',
            ],
        ],

    ],
];
