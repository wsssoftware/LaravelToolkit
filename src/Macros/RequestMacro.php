<?php

namespace LaravelToolkit\Macros;

use Illuminate\Http\Request;
use LaravelToolkit\Filepond\UploadedFile;

class RequestMacro
{
    public function __invoke(): void
    {
        $this->filepond();
    }

    public function filepond(): void
    {
        Request::macro('mergeFilepond', function (string $input): void {
            $this->merge([
                $input => UploadedFile::fromId($this->input($input)),
            ]);
        });

    }
}
