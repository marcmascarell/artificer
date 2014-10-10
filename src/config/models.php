<?php

return array(

	'directories' => array(
		app_path() . '/models'
	),

	'hidden' => array(
		'BookAttribute'
	),

	'permissions' => array(
		'admin' => array('*'),
		'user' => 'User'
	)
);