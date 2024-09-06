<?php

namespace LaravelToolkit\Tests\Enum;

use LaravelToolkit\Enum\ArrayableEnum;
use LaravelToolkit\Enum\HasArrayableEnum;

enum FakeValidEnum: string implements ArrayableEnum
{
    use HasArrayableEnum;

    case OPTION_1 = 'option_1';
    case OPTION_2 = 'option_2';
    case OPTION_3 = 'option_3';
    case OPTION_4 = 'option_4';

}
