<?php

namespace LaravelToolkit\Actions\Sitemap;

use Closure;

class RenderSitemap
{

    private function __construct(
        protected ?string $group = null
    ) {
        //
    }

    public static function make(string $group = null): Closure
    {
        return function () use ($group) {
            return (new static($group))();
        };
    }

    public function __invoke()
    {
        $path = base_path('routes/sitemap.php');
        abort_if(!file_exists($path), 404);
        $test = require $path;
        return ['oko'];
    }
}
