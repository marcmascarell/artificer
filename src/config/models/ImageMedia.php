<?php

return array (
	'title' => 'Image Media',

	// hidden and Todo: guarded
	'hidden' => array(''),

	// Editable, fillable, updatable
	'fillable' => array(),

	// Not updatable, not editable
	'guarded' => array('id'),

	'list' => array('*'),

	'list-hide' => array(''),

	// Optional, else looks for eloquent rules
	'rules' => array(
	),

	'fields' => array(
		'filename' => array(
			'type' => 'image'
		)
	)

);