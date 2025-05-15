<?php

namespace LaravelToolkit\Macros;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as LaravelLengthAwarePaginator;
use LaravelToolkit\DataAdapter\QueryHelper;
use LaravelToolkit\Pagination\LengthAwarePaginator;

class BuilderMacro
{
    public function __invoke(): void
    {
        $this->dataTable();
    }

    public function dataTable(): void
    {
        Builder::macro(
            'primevueData',
            function (
                string $pageName = 'page',
                ?array $globalFilterColumns = null,
                Closure|string|null $mapOrResource = null
            ): LengthAwarePaginator {
                app()->bind(LaravelLengthAwarePaginator::class, LengthAwarePaginator::class);
                $helper = app()->make(
                    QueryHelper::class,
                    ['pageName' => $pageName, 'globalFilterColumns' => $globalFilterColumns]
                );
                $helper->filters($this);
                $helper->sort($this);
                LengthAwarePaginator::mapOrResource($mapOrResource);

                return $this->paginate($helper->rows(), pageName: $pageName);
            }
        );
    }
}
