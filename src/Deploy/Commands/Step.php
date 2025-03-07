<?php

namespace LaravelToolkit\Deploy\Commands;

use Illuminate\Console\Command;

abstract class Step extends Command
{
    use HasIntents;
}
