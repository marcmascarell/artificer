<?php

return array(

	'classmap' => array(
		'sort_id' => '\Mascame\Artificer\Plugins\Sortable\SortableField',
//		'image'   => '\Mascame\Artificer\Plugins\Plupload\PluploadField',
		'hasOne'  => '\Mascame\Artificer\Fields\Types\Relations\hasOne',
		'hasMany' => '\Mascame\Artificer\Fields\Types\Relations\hasMany',
	),

	'types'    => array(
		// field_type => array('fieldname_1', 'fieldname_1')
		'integer'      => array(
			'id'
		),

		'checkbox'     => array(
			'accept',
			'active',
			'activated',
//			'published',
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

		'boolean'      => array(
//			'published'
		),

		'email'        => array(),

		'link'         => array(
			'url'
		),

		'date'         => array(
			'_at'
		),

		'file'         => array(),

		'image'        => array(
			'image'
		),

		'image_center' => array(),

		'sort_id'      => array(),

		'hasOne'       => array(
			'_id',
			'user_id',
			'fake_id'
		),
		'published' => array(),

		'hasMany'      => array(),

		'default'      => array('text')
	),
);