<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Facades\Facade;
use LaravelToolkit\SEO\SEOResource;

/**
 * @method static SEOResource payload()
 * @method static SEO withTitle(string $title)
 *
 * @see \LaravelToolkit\SEO\SEO
 */
class SEO extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\SEO\SEO::class;
    }
}
