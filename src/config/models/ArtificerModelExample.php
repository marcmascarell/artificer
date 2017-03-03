<?php

return [
    /*
     * Model title
     */
    'title'        => 'Model title',

    /*
     * The real value will never be shown (just that)
     */
    'hidden'    => ['password'],

    // Editable, fillable, updatable
//	'fillable'  => array(),
//
//	// Not updatable, not editable
//	'guarded'   => array('id'),

//	'list'      => array('*'),
//
//	'list-hide' => array('image_center'),

    /*
     * Fields that are shown on creation
     */
    'create' => [],

    /*
     * Fields that are shown on edit
     */
    'edit' => [],

    /*
     * Fields that will be shown when on list view mode
     */
    'list' => [
        'show' => ['*'],
        'hide' => ['password'],
    ],

    /*
     * The fields
     */
    'fields'    => [
        'user_id' => [
            /*
             * Title of field
             */
            'title'        => 'Model title',

            /*
             * Widgets of field
             */
            'widgets' => [],

            /*
             * Attributes for the input field (class, data-*, ...)
             */
            'attributes' => [],

            /*
             * Relationship (if it is)
             */
            'relationship' => [
                'method' => 'user', //this is the name of the Eloquent relationship method!
                'type'  => 'hasOne',
                'model' => 'User',
                'show'  => 'email',
            ],

            /*
             * Overrides an input by complete
             */
            'input' => '<input name="(:name)" value="(:value)">',
        ],

    ],

];
