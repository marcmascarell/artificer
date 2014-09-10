<?php

return array(
	'title'     => null,

	// Just hidden still modificable
	'hidden'    => array('password'),

	// Editable, fillable, updatable
	'fillable'  => array(),

	// Not updatable, not editable
	'guarded'   => array('id'),

	'list'      => array('*'),

	'list-hide' => array('image_center'),

	'fields'    => array(
		'user_id' => array(
			'title'        => "Fake Owner",
			'relationship' => array(
//                'method' => 'usuario', //this is the name of the Eloquent relationship method!
				'type'  => 'hasOne',
				'model' => 'User',
				'show'  => "username",
			),

//            'input' => '<input name="(:name)" value="(:value)">'
		),

	)

);