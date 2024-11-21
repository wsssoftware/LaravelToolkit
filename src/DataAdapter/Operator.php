<?php

namespace LaravelToolkit\DataAdapter;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Schema;

enum Operator: string
{
    case AND = 'and';
    case OR = 'or';
}
