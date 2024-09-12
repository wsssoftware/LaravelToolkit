<?php

namespace LaravelToolkit\Sitemap;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

readonly class ClosureRepository
{
    public function __construct(
        protected Collection|Builder $repository,
        protected Closure $closure,
    ) {
        //
    }

    public function resolve(): void
    {
        if ($this->repository instanceof Collection) {
            $this->repository->each(fn (mixed $item) => $this->closure->call($this, $item));
        } else {
            $this->repository->each(
                fn (mixed $item) => $this->closure->call($this, $item),
                config('laraveltoolkit.sitemap.query_count')
            );
        }
    }
}
