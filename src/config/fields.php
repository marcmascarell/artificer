<?php

/*
     'example_type'      => array(
        'regex' => '/myCoolRegex$/',

        // Fields with similar name will be matched (Starting, containing or ending)
        'autodetect' => array(
            'example'
        ),

        'attributes' => array(
            'class' => 'class1 class2',
        ),

        'widgets' => array(
            'artificer-example-widget',
        ),

        'onParse' => function($field, $type) {
            // Do something after field is parsed
        }
    )
 */
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

        'color'     => array(),

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
            'regex' => '/_at$/',

            "attributes" => array(
                'class' => 'form-control datetimepicker',
                'data-date-format' => 'YYYY-MM-DD HH:mm:ss',

            ),

            'widgets' => array(
                'artificer-datetimepicker-widget',
            )

        ),

        'date'         => array(
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
            'regex' => '/_id$/',

            "attributes" => array(
                'class' => 'chosen form-control',
            ),

            'onParse' => function($field, $type) {
                $relationship = \Mascame\Artificer\Options\FieldOption::get('relationship', $field);

                if ( ! isset($relationship['model'])) {
                    $model = preg_replace('/_id$/', '', $field);
                    $model = studly_case($model);

                    \Mascame\Artificer\Options\FieldOption::set('relationship.model', $model, $field);
                }

                if ( ! isset($relationship['show'])) {
                    \Mascame\Artificer\Options\FieldOption::set('relationship.show', 'id', $field);
                }
            }
        ),

        'hasMany'      => array(),

        'default'      => array(
            'type' => 'text'
        ),

    ),
);