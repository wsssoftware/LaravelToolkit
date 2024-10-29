<?php

namespace LaravelToolkit\Pagination;

use Illuminate\Support\Arr;

class LengthAwarePaginator extends \Illuminate\Pagination\LengthAwarePaginator
{
    public function toArray(): array
    {
        return [
            'page_name' => Arr::get($this->options, 'pageName'),
            ...parent::toArray()
        ];
    }
}
