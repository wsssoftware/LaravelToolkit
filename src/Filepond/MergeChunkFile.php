<?php

namespace LaravelToolkit\Filepond;

use LaravelToolkit\Facades\Filepond;

class MergeChunkFile
{
    protected mixed $file;

    public function __construct(
        protected string $id,
        protected string $outputFilename
    ) {
        $filenamePath = Filepond::path($this->id, $this->outputFilename);
        Filepond::disk()->delete($filenamePath);
        $this->file = fopen(Filepond::disk()->path(Filepond::path($this->id, $outputFilename)), 'w');
        abort_if($this->file === false, 500, 'Could not open file', ['Content-Type' => 'text/plain']);
    }

    public function __invoke(): void
    {
        $chunks = Filepond::chunks($this->id);
        $processedChunks = $chunks->reduce(fn (int $carry, string $path) => $this->mergeChunk($carry, $path), 0);
        abort_if($processedChunks !== $chunks->count(), 500, 'Something went wrong', ['Content-Type' => 'text/plain']);
        abort_if(fclose($this->file) === false, 500, 'Could not close file', ['Content-Type' => 'text/plain']);
    }

    protected function mergeChunk(int $carry, string $path): int
    {
        fwrite($this->file, Filepond::disk()->get($path));

        return $carry + 1;
    }
}
