<?php

return [

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
