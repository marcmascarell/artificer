<?php

return [

    'collections' => [
        // jQuery (CDN)
        'jquery-cdn' => ['//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js'],

        // Font Awesome (CDN)
        'font-awesome-cdn' => [
            '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css',
        ],

        // Bootstrap 3 CSS (CDN)
        'bootstrap-css-cdn' => [
            'font-awesome-cdn',
            '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
        ],

        // Bootstrap 3 JS (CDN)
        'bootstrap-js-cdn' => [
            'jquery-cdn',
            '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
        ],
    ],

    'assets' => [
        'jquery-cdn',
        'packages/mascame/admin/js/core/restfulizer.js'
    ]

];