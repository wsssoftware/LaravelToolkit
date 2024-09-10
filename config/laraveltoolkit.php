<?php

// config for LaravelToolkit
return [
    /*
    |--------------------------------------------------------------------------
    | Flash configurations
    |--------------------------------------------------------------------------
    |
    | This configurations will help to configure flash defaults
    |
    */
    'flash' => [
        'defaults' => [
            'closable' => null,
            'life' => null,
            'group' => 'lt-default',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO configurations
    |--------------------------------------------------------------------------
    |
    | This configurations will help to configure the behavior of SEO facade and
    | also its defaults
    |
    */
    'seo' => [
        // Allow or not format provided values to respect SEO rules
        'format' => true,
        // Allow to propagate title, description and others values to OpenGraph and Twitter Card
        'propagation' => true,
        'defaults' => [
            'title' => null,
            'description' => null,
            'canonical' => null,
            'robots' => null,
            'open_graph' => [

            ],
            'twitter_card' => [
                'site' => null,
                'title' => null,
                'description' => null,
                'image' => [
                    'disk' => null,
                    'path' => null,
                    'alt' => null,
                ]
            ]
        ]
    ],
];
