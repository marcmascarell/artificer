<?php

return array(
	'title'     => 'Artículos',

	// Just hidden still modificable
	'hidden'    => array('password'),

	// Editable, fillable, updatable
	'fillable'  => array(),

	// Not updatable, not editable
	'guarded'   => array('id'),

	'list'      => array('*'),

	'list-hide' => array(''),

	'fields'    => array(
		'fake_id' => array(
			'title'        => "Fake Owner",
			'relationship' => array(
//                'method' => 'usuario', //this is the name of the Eloquent relationship method!
				'type'  => 'hasOne',
				'model' => 'Fake',
				'show'  => "username",
			),


			//'input' => '<input name="(:name)" value="(:value)">'
		),

	)

);