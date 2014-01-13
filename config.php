<?php

return [

    'live' => $_SERVER['SERVER_PORT'] != 8080,

    'tmpl' => [

        'dir' => __DIR__ . '/template/',

        'ext' => '.tmpl.php'

    ],

    'cache' => [

        'dir' => __DIR__ . '/cache/'

    ],


    'post' => [

        'dir' => __DIR__ . '/posts/',

        'url' => 'posts/',

        'per' => 9

    ]

];