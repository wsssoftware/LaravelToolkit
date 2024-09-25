<?php

namespace LaravelToolkit\StoredAssets\AssetIntents;

use LaravelToolkit\StoredAssets\AssetIntents\PathAssetIntent;

class ContentAsset extends PathAssetIntent
{

    private mixed $resource;

    public function __construct(string $content)
    {
        $this->resource = tmpfile();
        fwrite($this->resource, $content);
        fseek($this->resource, 0);
        register_shutdown_function(fn() => fclose($this->resource));
        parent::__construct(stream_get_meta_data($this->resource)['uri']);
    }
}
