<?php

namespace LaravelToolkit\Macros;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as LaravelLengthAwarePaginator;
use Illuminate\Support\Arr;
use LaravelToolkit\Pagination\LengthAwarePaginator;

class BuilderMacro
{
    public function __invoke(): void
    {
        $this->dataTable();
    }

    public function dataTable(): void
    {
        Builder::macro('primevueDataTable', function ($pageName = 'page'): LengthAwarePaginator {
            app()->bind(LaravelLengthAwarePaginator::class, LengthAwarePaginator::class);
            $options = request()->query("$pageName-options");

            BuilderMacro::sort($this, Arr::get($options, 'sort'));

            return $this->paginate(intval(Arr::get($options, 'rows', 15)), pageName: $pageName);
        });
    }

    public static function sort(Builder $builder, ?string $sort): void
    {
        if (empty($sort)) {
            return;
        }
        ray()->showQueries();
        collect(explode(',', $sort))
            ->map(fn (string $item) => explode(':', $item))
            ->mapWithKeys(fn (array $item) => [$item[0] => $item[1]])
            ->each(function (string $dir, string $column) use ($builder) {
                $builder->orderBy($column, $dir);
            });
    }
}
