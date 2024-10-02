<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use LaravelToolkit\SEO\Image;
use LaravelToolkit\SEO\RobotRule;

/**
 * @method static string friendlyUrlString(string $string)
 * @method static bool isCrawler(string $agent = null)
 * @method static bool isRobotsTxtSitemapSetted()
 * @method static array payload()
 * @method static string robotsTxt()
 * @method static \LaravelToolkit\SEO\SEO withoutCanonical(bool $propagate = null)
 * @method static \LaravelToolkit\SEO\SEO withoutDescription(bool $propagate = null)
 * @method static \LaravelToolkit\SEO\SEO withoutOpenGraphType()
 * @method static \LaravelToolkit\SEO\SEO withoutOpenGraphTitle()
 * @method static \LaravelToolkit\SEO\SEO withoutOpenGraphDescription()
 * @method static \LaravelToolkit\SEO\SEO withoutOpenGraphUrl()
 * @method static \LaravelToolkit\SEO\SEO withoutOpenGraphImage()
 * @method static \LaravelToolkit\SEO\SEO withoutPropagation()
 * @method static \LaravelToolkit\SEO\SEO withoutRobots()
 * @method static \LaravelToolkit\SEO\SEO withoutRobotsTxtRule(string $userAgent = null)
 * @method static \LaravelToolkit\SEO\SEO withoutRobotsTxtSitemap()
 * @method static \LaravelToolkit\SEO\SEO withoutTitle(bool $propagate = null)
 * @method static \LaravelToolkit\SEO\SEO withoutTwitterCardCreator()
 * @method static \LaravelToolkit\SEO\SEO withoutTwitterCardSite()
 * @method static \LaravelToolkit\SEO\SEO withoutTwitterCardTitle()
 * @method static \LaravelToolkit\SEO\SEO withoutTwitterCardDescription()
 * @method static \LaravelToolkit\SEO\SEO withoutTwitterCardImage()
 * @method static \LaravelToolkit\SEO\SEO withCanonical(string $canonical, bool $propagate = null)
 * @method static \LaravelToolkit\SEO\SEO withDescription(string $description, bool $propagate = null)
 * @method static \LaravelToolkit\SEO\SEO withOpenGraphType(string $type)
 * @method static \LaravelToolkit\SEO\SEO withOpenGraphTitle(string $title)
 * @method static \LaravelToolkit\SEO\SEO withOpenGraphDescription(string $description)
 * @method static \LaravelToolkit\SEO\SEO withOpenGraphUrl(string $url)
 * @method static \LaravelToolkit\SEO\SEO withOpenGraphImage(Image $image)
 * @method static \LaravelToolkit\SEO\SEO withPropagation()
 * @method static \LaravelToolkit\SEO\SEO withRobots(RobotRule|string ...$items)
 * @method static \LaravelToolkit\SEO\SEO withRobotsTxtRule(string $userAgent = null, Collection $allow = null, Collection $disallow = null)
 * @method static \LaravelToolkit\SEO\SEO withRobotsTxtSitemap(string $url)
 * @method static \LaravelToolkit\SEO\SEO withTitle(string $title, bool $propagate = null)
 * @method static \LaravelToolkit\SEO\SEO withTwitterCardSite(string $site)
 * @method static \LaravelToolkit\SEO\SEO withTwitterCardCreator(string $creator)
 * @method static \LaravelToolkit\SEO\SEO withTwitterCardTitle(string $title)
 * @method static \LaravelToolkit\SEO\SEO withTwitterCardDescription(string $description)
 * @method static \LaravelToolkit\SEO\SEO withTwitterCardImage(Image $image)
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
