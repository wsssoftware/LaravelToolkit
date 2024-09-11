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
        // Allow to propagate title, description and others values to OpenGraph and Twitter Card
        'propagation' => true,
        'defaults' => [
            'title' => null,
            'description' => null,
            'canonical' => null,
            'robots' => [],
            'open_graph' => [
                'type' => 'website',
                'title' => null,
                'description' => null,
                'url' => null,
                'image' => [
                    'disk' => null,
                    'path' => null,
                    'alt' => null,
                ],
            ],
            'twitter_card' => [
                'site' => null,
                'creator' => null,
                'title' => null,
                'description' => null,
                'image' => [
                    'disk' => null,
                    'path' => null,
                    'alt' => null,
                ],
            ],
        ],
    ],
];
