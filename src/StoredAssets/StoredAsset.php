<?php

namespace LaravelToolkit\StoredAssets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $model
 * @property array $files
 * @property \Illuminate\Support\Carbon $created_at
 */
class StoredAsset extends Model
{
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'model',
        'files',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'model' => 'string',
            'files' => 'json',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the parent assetable model
     */
    public function assetable(): MorphTo
    {
        return $this->morphTo();
    }
}
