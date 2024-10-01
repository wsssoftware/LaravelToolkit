<?php

namespace LaravelToolkit\SEO;

use Illuminate\Support\Collection;

class RobotsTxt
{
    public false|Collection $userAgent = false;

    public false|Collection $allow = false;
    public false|Collection $disallow = false;

    public function __construct()
    {
        $cUserAgent =  config('laraveltoolkit.seo.defaults.robots_txt.user_agent', false);
        $cAllow =  config('laraveltoolkit.seo.defaults.robots_txt.allow', false);
        $cDisallow =  config('laraveltoolkit.seo.defaults.robots_txt.disallow', false);
        $this->userAgent = is_array($cUserAgent) ? collect($cUserAgent) : false;
        $this->allow = is_array($cAllow) ? collect($cAllow) : false;
        $this->disallow = is_array($cDisallow) ? collect($cDisallow) : false;
    }
}
