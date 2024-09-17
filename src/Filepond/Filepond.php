<?php

namespace LaravelToolkit\Filepond;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Lottery;
use Illuminate\Support\Str;

class Filepond
{
    public function disk(): FilesystemAdapter|Filesystem
    {
        return Storage::disk($this->diskName());
    }
    public function diskName(): string
    {
        return config('laraveltoolkit.filepond.disk');
    }

    public function garbageCollector(): void
    {
        Lottery::odds(floatval(config('laraveltoolkit.filepond.garbage_collector.probability')))
            ->winner(fn () => (new GarbageCollector())())
            ->choose();
    }

    public function generateId(): string
    {
        return Str::uuid()->toString();
    }

    public function path(string $id, string $path = null): string
    {
        return sprintf(
            '%s%s%s%s%s',
            config('laraveltoolkit.filepond.root_path'),
            DIRECTORY_SEPARATOR,
            $id,
            DIRECTORY_SEPARATOR,
            $path ?? '',
        );
    }
}
