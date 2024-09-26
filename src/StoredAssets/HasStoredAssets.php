<?php

namespace LaravelToolkit\StoredAssets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasStoredAssets
{
    private bool $storedAssetFieldAsModel = true;
    private static array $storedAssetFields = [];

    protected static function bootHasStoredAssets(): void
    {
        self::registryStoredAssetFieldsRelations();
        self::saving(function (Model $model) {
            foreach ($model->getDirty() as $field => $item) {
                if (!$item instanceof Recipe) {
                    continue;
                }
                if (Str::isUuid($uuid = $item->save())) {
                    $model->setAttribute($field, $uuid);
                } else {
                    return false;
                }
            }
            return true;
        });
    }

    private static function registryStoredAssetFieldsRelations(): void
    {
        $model = static::newModelInstance();
        foreach ($model->getCasts() as $field => $cast) {
            $validClass = class_exists($cast) && is_subclass_of($cast, Recipe::class);
            if ($validClass && !$model->isRelation($field)) {
                static::$storedAssetFields[$field] = $field;
                static::resolveRelationUsing(
                    $field,
                    fn(Model $model) => $model->morphOne(StoredAssetModel::class, 'asset', 'model', 'id', $field)
                );
            }
        }
    }

    public function asStoredAssetModel(): self
    {
        $this->storedAssetFieldAsModel = true;
        return $this;
    }

    public function asStoredAssetUuid(): self
    {
        $this->storedAssetFieldAsModel = false;
        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        foreach ($data as $key => $item) {
            if (in_array($key, static::$storedAssetFields) && $this->isRelation($key) && $this->storedAssetFieldAsModel) {
                $data[$key] = $this->getRelationValue($key)?->assets;
            }
        }

        return $data;
    }

    public function __get($key)
    {
        if (in_array($key, static::$storedAssetFields) && $this->isRelation($key) && $this->storedAssetFieldAsModel) {
            return $this->getRelationValue($key)?->assets;
        }
        return parent::__get($key);
    }
}
