<?php

return [
    /**
     * Url
     */
    'url'         => 'http://cityhapps.dev',

    /**
     * Database
     */
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'cityhapps',
            'username'  => 'user',
            'password'  => 'password',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
    ],

    /**
     * Debug
     */
    'debug'       => true,

    /**
     * key
     */
    'key'         => 'verysecretKey!!!',

];
