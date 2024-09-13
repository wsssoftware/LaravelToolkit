<?php

namespace LaravelToolkit\Facades;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use LaravelToolkit\Sitemap\ChangeFrequency;
use LaravelToolkit\Sitemap\Index;
use LaravelToolkit\Sitemap\Url;

/**
 * @method static \LaravelToolkit\Sitemap\Sitemap addIndex(string $name)
 * @method static \LaravelToolkit\Sitemap\Sitemap addUrl(Url|string $url, ?Carbon $lastModified = null, ?ChangeFrequency $changeFrequency = null, ?float $priority = null)
 * @method static void domain(string $name, Closure $closure)
 * @method static bool domainExists(string $name)
 * @method static \LaravelToolkit\Sitemap\Sitemap fromQuery(Builder $builder, Closure $closure, int $count = 1_000)
 * @method static \LaravelToolkit\Sitemap\Sitemap fromCollection(array|Collection $collection, Closure $closure)
 * @method static void index(string $name, Closure $closure)
 * @method static bool indexExists(string $name)
 * @method static Collection<int, Index|Url> process(string $domain, ?string $index)
 *
 * @see \LaravelToolkit\Sitemap\Sitemap
 */
class Sitemap extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\Sitemap\Sitemap::class;
    }
}
