<?php

return array(

    'classmap' => array(
        'bool' => '\Mascame\Artificer\Fields\Types\Checkbox',
        'boolean' => '\Mascame\Artificer\Fields\Types\Checkbox',
//		'image'   => '\Mascame\Artificer\Plugins\Plupload\PluploadField',
        'hasOne'  => '\Mascame\Artificer\Fields\Types\Relations\hasOne',
        'hasMany' => '\Mascame\Artificer\Fields\Types\Relations\hasMany',
        'belongsTo' => '\Mascame\Artificer\Fields\Types\Relations\belongsTo',
    ),

    'types'    => array(
        // field_type => array('fieldname_1', 'fieldname_1')
        'key'      => array(
            'autodetect' => array(
                'id'
            )
        ),

        'enum' => array(
            'autodetect' => array(
                'role'
            )
        ),

        'published' => array(),

        'checkbox'     => array(
            'autodetect' => array(
                'accept',
                'active',
                'boolean',
                'activated',
                'binary'
            ),
        ),

        'custom'     => array(

        ),

        'password'     => array(
            'autodetect' => array(
                'password'
            ),
        ),

        'text'         => array(
            'autodetect' => array(
                'title',
                'username',
                'name'
            ),
        ),

        'textarea'     => array(

        ),

        'wysiwyg'      => array(
            'autodetect' => array(
                'body',
                'text'
            ),
        ),

        'option'       => array(
            'autodetect' => array(
                'selection',
            ),
        ),

        'email'        => array(),

        'link'         => array(
            'autodetect' => array(
                'url'
            ),
        ),

        'datetime'         => array(
            'autodetect' => array(
                '_at'
            ),

            "attributes" => array(
                'class' => 'form-control datetimepicker',
                'data-date-format' => 'YYYY-MM-DD HH:mm:ss',

            ),

            'widgets' => array(
                'artificer-datetimepicker-widget',
            )

        ),

        'date'         => array(
            'autodetect' => array(
                '_at'
            ),

            "attributes" => array(
                'class' => 'form-control datetimepicker',
                'data-date-format' => 'YYYY-MM-DD',
            ),

            'widgets' => array(
                'artificer-datetimepicker-widget',
            )

        ),

        'file'         => array(),

        'image'        => array(
            'autodetect' => array(
                'image'
            ),
        ),

        'image_center' => array(),

        'hasOne'       => array(
            'autodetect' => array(
                '_id',
                'user_id',
                'fake_id'
            ),
        ),

        'hasMany'      => array(),

        'default'      => array(
            'type' => 'text'
        )
    ),
);