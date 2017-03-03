<?php

use Mascame\ArtificerWidgets\Datepicker\Datepicker;
use Mascame\ArtificerWidgets\DateTimepicker\DateTimepicker;

return [

    'classmap' => [
        'bool' => '\Mascame\Artificer\Fields\Types\Checkbox',
        'boolean' => '\Mascame\Artificer\Fields\Types\Checkbox',
//		'image'   => '\Mascame\Artificer\Plugins\Plupload\PluploadField',
        'hasOne'  => '\Mascame\Artificer\Fields\Types\Relations\hasOne',
        'hasMany' => '\Mascame\Artificer\Fields\Types\Relations\hasMany',
        'belongsTo' => '\Mascame\Artificer\Fields\Types\Relations\belongsTo',
    ],

    'types'    => [
        // field_type => array('fieldname_1', 'fieldname_1')
        'key'      => [
            'autodetect' => [
                'id',
            ],
        ],

        'published' => [],

        'checkbox'     => [
            'autodetect' => [
                'accept',
                'active',
                'boolean',
                'activated',
            ],
        ],

        'custom'     => [

        ],

        'password'     => [
            'autodetect' => [
                'password',
            ],
        ],

        'text'         => [
            'autodetect' => [
                'title',
                'username',
                'name',
            ],
        ],

        'textarea'     => [

        ],

        'wysiwyg'      => [
            'autodetect' => [
                'body',
                'text',
            ],
        ],

        'option'       => [
            'autodetect' => [
                'selection',
            ],
        ],

        'email'        => [],

        'link'         => [
            'autodetect' => [
                'url',
            ],
        ],

        'datetime'         => [
            'autodetect' => [
                '_at',
            ],

            'attributes' => [
                'class' => 'form-control datetimepicker', 'data-date-format' => 'YYYY-MM-DD HH:mm:ss',

            ],

            'widgets' => [
                new DateTimepicker(),
            ],

        ],

        'date'         => [
            'autodetect' => [
                '_at',
            ],

            'attributes' => [
                'class' => 'form-control datepicker',

            ],

            'widgets' => [
                new Datepicker(),
            ],

        ],

        'file'         => [],

        'image'        => [
            'autodetect' => [
                'image',
            ],
        ],

        'image_center' => [],

        'hasOne'       => [
            'autodetect' => [
                '_id',
                'user_id',
                'fake_id',
            ],
        ],

        'hasMany'      => [],

        'default'      => [
            'type' => 'text',
        ],
    ],
];
