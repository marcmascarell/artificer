<?php

return [

    /**
     * Model title
     */
    'title' => "{{ name }}",

    /**
     * The real value will never be shown (just that)
     */
	'hidden' => ['password'],

	// Editable, fillable, updatable
	'fillable' => ['*'],

	// Not updatable, not editable
	'guarded' => ['id'],

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
	'fields' => [
		//
	]

];