<?php

namespace LaravelToolkit\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use LaravelToolkit\Flash\Message;

/**
 * @method static void clear()
 * @method static Collection<int, Message> getMessages()
 * @method static Message success(string $detail, string $summary = null)
 * @method static Message info(string $detail, string $summary = null)
 * @method static Message warn(string $detail, string $summary = null)
 * @method static Message error(string $detail, string $summary = null)
 * @method static Message secondary(string $detail, string $summary = null)
 * @method static Message contrast(string $detail, string $summary = null)
 *
 * @see \LaravelToolkit\Flash\Flash
 */
class Flash extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelToolkit\Flash\Flash::class;
    }
}
