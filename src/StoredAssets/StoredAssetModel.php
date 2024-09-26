<?php

namespace LaravelToolkit\StoredAssets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $model
 * @property \LaravelToolkit\StoredAssets\Assets $assets
 * @property \Illuminate\Support\Carbon $created_at
 */
class StoredAssetModel extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'stored_assets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'model',
        'assets',
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
            'assets' => Assets::class,
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
