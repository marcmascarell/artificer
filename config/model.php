<?php

return [

	'directories'      => [
		// '\\App\\Models' => app_path() . '/Models',
	],

	/**
	 * You can also specify models individually
	 */
	'models'      => [
		\App\User::class,
	],

	'hidden'           => [
//		'BookAttribute',
	],

	'route_permission' => [
		'admin.model.store'   => 'create',
		'admin.model.create'  => 'create',
		'admin.model.update'  => 'update',
		'admin.model.edit'    => 'update',
		'admin.model.destroy' => 'delete',
		'admin.model.show'    => 'view',
		'admin.model.all'     => 'view',
	],

	'default' => [
		/**
		 * The real value will never be shown (just that)
		 */
		'hidden'    => ['password'],

		// Editable, fillable, updatable
		'fillable'  => ['*'],
		//
		//	// Not updatable, not editable
		'guarded'   => ['id'],

		/**
		 * Fields that are shown on creation
		 */
		'create' => [
			'visible' => ['*'],
			'hidden' => ['id'],
		],

		/**
		 * Fields that are shown on edit
		 */
		'edit' => [
			'visible' => ['*'],
			'hidden' => ['id'],
		],

		/**
		 * Fields that will be shown when on list view mode
		 */
		'list' => [
			'visible' => ['*'],
			'hidden' => ['password'],
		],

		'fields'    => [

		],

		// Attributes of fields, applied unless field has attributes
		'attributes' => [
			'class' => 'form-control'
		]
	]
];