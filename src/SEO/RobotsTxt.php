<?php

namespace LaravelToolkit\SEO;

use Illuminate\Support\Collection;

class RobotsTxt
{
    /**
     * @var \Illuminate\Support\Collection<string, \LaravelToolkit\SEO\RobotsTxtRule>
     */
    public Collection $rules;

    public ?string $sitemap;

    public function __construct()
    {
        $this->rules = collect(config('laraveltoolkit.seo.defaults.robots_txt.rules', []))
            ->mapWithKeys(fn($rule) => [
                $rule['user_agent'] => new RobotsTxtRule($rule['user_agent'], $rule['allow'], $rule['disallow'])
            ]);
        $this->sitemap = config('laraveltoolkit.seo.defaults.robots_txt.sitemap', null);
    }
}
