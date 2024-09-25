<?php

namespace LaravelToolkit\StoredAssets\AssetIntents;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use League\Flysystem\UnableToReadFile;

class PathAssetIntent
{

    protected string $disk;
    protected string $label;

    public function __construct(
        readonly protected string $pathname,
    ) {
        //
    }

    public function getContentStream(): mixed
    {
        error_clear_last();
        $contents = @fopen($this->pathname, 'rb');

        if ($contents === false) {
            throw UnableToReadFile::fromLocation($this->pathname, error_get_last()['message'] ?? '');
        }

        return $contents;
    }

    public function getDisk(): string
    {
        return $this->disk ?? config('laraveltoolkit.stored_assets.disk');
    }

    public function getExtension(): string
    {
        return File::guessExtension($this->pathname) ?? File::extension($this->pathname);
    }

    public function getFilename(): string
    {
        return File::basename($this->pathname);
    }

    public function getLabel(): string
    {
        return $this->label ?? 'default';
    }

    public function getLastModified(): Carbon
    {
        return Carbon::createFromTimestamp(File::lastModified($this->pathname));
    }

    public function getName(): string
    {
        return File::name($this->pathname);
    }

    public function getMimeType(): string
    {
        return File::mimeType($this->pathname);
    }

    public function getPath(): string
    {
       return File::dirname($this->pathname);
    }

    public function getPathname(): string
    {
        return $this->pathname;
    }

    public function getSize(): int
    {
        return File::size($this->pathname);
    }

    public function withDisk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    public function withLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }
}
