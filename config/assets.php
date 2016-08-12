<?php

return [

	'collections' => [
        // jQuery (CDN)
        'jquery-cdn' => [
            '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js'
        ],

        // Twitter Bootstrap (CDN)
        'bootstrap-cdn' => [
            'jquery-cdn',
            '//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
            '//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css',
            '//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'
        ],
    ],

    'assets' => [
        'jquery-cdn',
        'packages/mascame/admin/js/core/restfulizer.js'
    ]
];