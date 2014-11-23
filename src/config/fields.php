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
			'id'
		),

		'published' => array(),

		'checkbox'     => array(
			'accept',
			'active',
			'boolean',
			'activated',
		),

		'password'     => array(
			'password'
		),

		'text'         => array(
			'title',
			'username',
			'name'
		),

		'textarea'     => array(
			''
		),

		'wysiwyg'      => array(
			'body',
			'text'
		),

		'option'       => array(
			'selection',
		),

		'email'        => array(),

		'link'         => array(
			'url'
		),

		'datetime'         => array(
			'_at'
		),

		'file'         => array(),

		'image'        => array(
			'image'
		),

		'image_center' => array(),

		'hasOne'       => array(
			'_id',
			'user_id',
			'fake_id'
		),

		'hasMany'      => array(),

		'default'      => array('text')
	),
);