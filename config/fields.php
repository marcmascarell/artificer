<?php

use \Mascame\Artificer\Fields\Types as ArtificerTypes;
use \Mascame\Formality\Types as DefaultTypes;

return [

    'classmap' => [
//        'bool' => Types\Checkbox::class,
//        'boolean' => Types\Checkbox::class,
//		'image'   => \Mascame\Artificer\Plugins\Plupload\PluploadField::class,
        'hasOne'  => ArtificerTypes\Relations\hasOne::class,
        'hasMany' => ArtificerTypes\Relations\hasMany::class,
        'belongsTo' => ArtificerTypes\Relations\belongsTo::class,
    ],

    'types' => [
        // field_type => ['fieldname_1', 'fieldname_1')
        'key' => [
            'autodetect' => [
                'id'
            ]
        ],

        'published' => [],

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

        'password' => [
            'autodetect' => [
                'password'
            ],
        ],

        'text' => [
            'autodetect' => [
                'title',
                'username',
                'name'
            ],
        ],

        'textarea' => [
            'autodetect' => [
                'description'
            ],
        ],

        'wysiwyg' => [
            'autodetect' => [
                'body',
                'text'
            ],
        ],

        'radio' => [
            'autodetect' => [
                'option',
                'selection',
            ],
        ],

        'email' => [],

        'link' => [
            'autodetect' => [
                'url'
            ],
        ],

        'ip' => [
            'regex' => [
                '/_ip$/',
            ],
        ],

        'datetime' => [

            'regex' => [
                '/_at$/',
                '/_on$/',
            ],

            "attributes" => [
                'class' => 'form-control',
            ],

            'widgets' => [
                \Mascame\Artificer\Widgets\DateTimepicker::class,
            ]

        ],

        'date' => [
            'autodetect' => [
                '_at'
            ],

            "attributes" => [
                'class' => 'form-control',
            ],

            'widgets' => [
                \Mascame\Artificer\Widgets\DateTimepicker::class,
            ]
        ],

        'file' => [],

        'image' => [
            'autodetect' => [
                'image'
            ],
        ],

        'hasOne' => [
            'autodetect' => [
                '_id',
                'user_id',
                'fake_id'
            ],
        ],

        'hasMany' => [],

        'default' => [
            'type' => 'text'
        ]
    ],
];