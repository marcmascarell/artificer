<?php

return array(
    /**
     * Model title
     */
    'title'        => "Model title",

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

    /**
     * The fields
     */
	'fields'    => array(
		'user_id' => array(
            /**
             * Title of field
             */
			'title'        => "Model title",

            /**
             * Widgets of field
             */
            'widgets' => array(),

            /**
             * Attributes for the input field (class, data-*, ...)
             */
            'attributes' => array(),

            /**
             * Relationship (if it is)
             */
			'relationship' => array(
                'method' => 'user', //this is the name of the Eloquent relationship method!
				'type'  => 'hasOne',
				'model' => 'User',
				'show'  => "email",
			),

            /**
             * Overrides an input by complete
             */
            'input' => '<input name="(:name)" value="(:value)">'
		),

	)

);