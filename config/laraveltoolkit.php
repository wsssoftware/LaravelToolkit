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
    /*
    |--------------------------------------------------------------------------
    | Sitemap configurations
    |--------------------------------------------------------------------------
    |
    | This configurations will help to configure the behavior of Sitemap facade
    |
    | -> cache: If an int, will be the cache time in seconds or false to disable.
    | -> default_routes: If true, Laravel Toolkit will create sitemap routes.
    | -> timeout: If an int will change the max execution time from php. If null
    |    it will use the php.ini default. This is helpful for large sitemaps
    | -> max_file_items: Max amount of items on sitemap without trigger log
    | -> max_file_size: Max sitemap size without trigger log
    |
    */
    'sitemap' => [
        'cache' => 21_600,
        'default_routes' => true,
        'timeout' => null,
        'max_file_items' => 50_000,
        'max_file_size' => 50 * 1024 * 1024,
    ],
];
