<?php

return [
    /**
     * Model title
     */
    'title'        => "Model title",

    /**
     * The real value will never be shown (just that)
     */
	'hidden'    => ['password'],

	// Editable, fillable, updatable
	'fillable'  => ['*'],

	// Not updatable, not editable
	'guarded'   => ['id'],

    /**
     * Fields that are shown on creation form
     */
    'create' => [
        'visible' => ['*'],
        'hidden' => ['id'],
    ],

    /**
     * Fields that are shown on edit form
     */
    'edit' => [
        'visible' => ['*'],
        'hidden' => ['id'],
    ],

    /**
     * Fields that will be shown when viewing the items list
     */
    'list' => [
        'visible' => ['*'],
        'hidden' => [],
    ],

    /**
     * Fields that will be shown when seeing one record in detail
     */
    'show' => [
        'visible' => ['*'],
        'hidden' => [],
    ],

    /**
     * The fields
     */
	'fields'    => [
		'user_id' => [
            /**
             * Title of field
             */
			'title'        => "Model title",

            /**
             * Widgets of field
             */
            'widgets' => [],

            /**
             * Attributes for the input field (class, data-*, ...)
             */
            'attributes' => [],

            /**
             * Relationship (if it is)
             */
			'relationship' => [
                'method' => 'user', //this is the name of the Eloquent relationship method!
				'type'  => 'hasOne',
				'model' => 'User',
				'show'  => "email",
			],

            /**
             * Overrides an input completely
             */
            'input' => '<input name="(:name)" value="(:value)">'
		],

	]

];