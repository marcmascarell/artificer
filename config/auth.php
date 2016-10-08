<?php

return [

    /*
     * Closure to validate credentials
     */
    'attempt' => function ($credentials) {
        return Auth::attempt($credentials);
    },
    /*
     * Closure to determine if user is logged
     */
    'check' => function () {
        return Auth::check();
    },

    /*
     * Available roles
     */
    'roles'       => [
        'admin',
        'editor',
        'user',
    ],

    /*
     * Database column that refers to role
     */
    'role_column' => 'role',

    /*
     * Maximum attempts before ban
     */
    'max_login_attempts'   => 3,

    /*
     * Minutes
     */
    'ban_time'    => 5,
];
