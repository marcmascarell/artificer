<?php

return array(

	'directories'      => array(
		app_path() . '/models'
	),

	'hidden'           => array(
		'BookAttribute',
		'Metatag',
		'PaymentArticle',

//        'Page',
		'Sharer'
	),

	'route_permission' => array(
		'admin.model.store'   => 'create',
		'admin.model.create'  => 'create',
		'admin.model.update'  => 'update',
		'admin.model.edit'    => 'update',
		'admin.model.destroy' => 'delete',
		'admin.model.show'    => 'view',
		'admin.model.all'     => 'view',
	),

	'default_model' => array(
		/**
		 * The real value will never be shown (just that)
		 */
		'hidden'    => array('password'),

		// Editable, fillable, updatable
		//	'fillable'  => array(),
		//
		//	// Not updatable, not editable
		//	'guarded'   => array('id'),

		//	'list'      => array('*'),
		//
		//	'list-hide' => array('image_center'),

		/**
		 * Fields that are shown on creation
		 */
		'create' => array(),

		/**
		 * Fields that are shown on edit
		 */
		'edit' => array(),

		/**
		 * Fields that will be shown when on list view mode
		 */
		'list' => array(
			'show' => array('*'),
			'hide' => array('password'),
		),

		'fields'    => array(

		)
	)
);