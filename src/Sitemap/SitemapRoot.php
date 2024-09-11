<?php

namespace LaravelToolkit\Sitemap;

use Closure;
use Exception;
use Illuminate\Support\Collection;

class SitemapRoot extends Sitemap
{
    protected Collection $groups;

    public function __construct(?string $group = null)
    {
        $this->groups = collect();
        parent::__construct($group);
    }

    public function group(string $group, Closure $closure): void
    {
        $group = $this->groups->getOrPut($group, fn() => new Sitemap($group));
        $closure->call($this, $group);
    }


}
