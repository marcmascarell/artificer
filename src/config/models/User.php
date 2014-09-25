<?php

return array(
	'title'     => 'Users',

	// hidden and Todo: guarded
	'hidden'    => array(''),

	// Editable, fillable, updatable
	'fillable'  => array(),

	// Not updatable, not editable
	'guarded'   => array('id'),

	'list'      => array('*'),

	'list-hide' => array('password'),

	// Optional, else looks for eloquent rules
	'rules'     => array(
		'email' => 'required|email'
	),

	'fields'    => array(
//		'user_id' => array(
//			'title'        => "Fake Owner",
//			'relationship' => array(
//                'method' => 'usuario', //this is the name of the Eloquent relationship method!
//				'type'  => 'hasOne',
//				'model' => 'User',
//				'show'  => "username",
//			),
//
//			//'input' => '<input name="(:name)" value="(:value)">'
//		)
	)

);