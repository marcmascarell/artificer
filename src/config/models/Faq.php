<?php

return array(
	'title'     => 'Faq',

	// hidden and Todo: guarded
	'hidden'    => array(''),

	// Editable, fillable, updatable
	'fillable'  => array(),

	// Not updatable, not editable
	'guarded'   => array('id'),

	'list'      => array('*'),

	'list-hide' => array(''),

	// Optional, else looks for eloquent rules
	'rules'     => array(
		'email' => 'required|email'
	),

	'fields'    => array(
		'answer' => array(
			'type' => 'textarea'
		)
	)

);