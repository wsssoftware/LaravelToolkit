<?php

namespace LaravelToolkit\Filepond;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use LaravelToolkit\Facades\Filepond;

class MergeChunkFile
{
    protected Filesystem|FilesystemAdapter $disk;
    protected mixed $file;
    protected string $outputPath;

    public function __construct(
        protected string $id,
        string $outputFilename
    ) {
        $this->disk = Filepond::disk();
        $this->outputPath = Filepond::path($this->id, $outputFilename);
        $this->disk->delete($this->outputPath);
        $this->file = tmpfile();
        abort_if($this->file === false, 500, 'Could not open file', ['Content-Type' => 'text/plain']);
    }

    public function __invoke(): void
    {
        $chunks = Filepond::chunks($this->id);
        $processedChunks = $chunks->reduce(fn (int $carry, string $path) => $this->mergeChunk($carry, $path), 0);
        abort_if($processedChunks !== $chunks->count(), 500, 'Something went wrong', ['Content-Type' => 'text/plain']);
        rewind($this->file);
        abort_if(!Filepond::disk()->put($this->outputPath, $this->file), 500, 'Something went wrong', ['Content-Type' => 'text/plain']);
        fclose($this->file);
    }

    protected function mergeChunk(int $carry, string $path): int
    {
        fwrite($this->file, Filepond::disk()->get($path));

        return $carry + 1;
    }
}
