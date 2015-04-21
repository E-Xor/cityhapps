<?php

return [
    /**
     * Url
     */
    'url'         => 'http://localhost:9200',

    /**
     * Database
     */
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => '192.168.33.99',
            'database'  => 'cityhapps',
            'username'  => 'root',
            'password'  => 'root',
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
