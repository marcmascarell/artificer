<?php

return [

	/**
	 * Do you have models grouped in folders? Add them below.
	 * 
	 * '\\Your\\Namespace' => $path
	 */
	'directories'      => [
		// '\\App\\Models' => app_path() . '/Models',
	],

	/**
	 * You can also specify models individually
	 */
	'models'      => [
		\App\User::class,
	],

	/**
	 * Models not shown and not accessible
	 */
	'hidden'           => [
//		'BookAttribute',
	],

	// Todo: what to do with this? move to internal?
	'route_permission' => [
		'admin.model.store'   => 'create',
		'admin.model.create'  => 'create',
		'admin.model.update'  => 'update',
		'admin.model.edit'    => 'update',
		'admin.model.destroy' => 'delete',
		'admin.model.show'    => 'view',
		'admin.model.all'     => 'view',
	],

	/**
	 * Default params will be merged with each model config
	 */
	'default' => [
		
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

		'show' => [
			'visible' => ['*']
		],

		'fields'    => [

		],

		// Attributes of fields, applied unless field has attributes
		'attributes' => [
			'class' => 'form-control'
		]
	]
];