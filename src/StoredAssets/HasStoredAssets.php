<?php

namespace LaravelToolkit\StoredAssets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasStoredAssets
{

    private array $storedAssetFields = [];
    private bool $storedAssetFieldAsModel = true;

    protected static function bootHasStoredAssets(): void
    {
        self::saving(function (Model $model) {
            foreach ($model->getDirty() as $field => $item) {
                if ($item instanceof Recipe && Str::isUuid($uuid = $item->save())) {
                    $model->setAttribute($field, $uuid);
                } else {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Initialize the trait.
     *
     * @return void
     */
    protected function initializeHasStoredAssets(): void
    {
        $this->registryStoredAssetFieldsRelations();
    }

    private function registryStoredAssetFieldsRelations(): void
    {
        foreach ($this->casts() as $field => $cast) {
            $validClass = class_exists($cast) && is_a($cast, Recipe::class, true);
            if ($validClass && !in_array($field, $this->storedAssetFields) && !$this->isRelation($field)) {
                $this->storedAssetFields[$field] = $field;
                self::resolveRelationUsing(
                    $field,
                    fn(Model $model) => $model->morphOne(StoredAsset::class, 'image', 'model', 'id', $field)
                );
            }
        }
    }

    public function toArray(): array
    {
        foreach ($this->storedAssetFields as $storedAssetField) {
            if (!$this->relationLoaded($storedAssetField)) {
                $this->load($storedAssetField);
            }
        }
        return parent::toArray();
    }

    public function __get($key)
    {
        if (in_array($key, $this->storedAssetFields) && $this->isRelation($key) && $this->storedAssetFieldAsModel) {
            return $this->getRelationValue($key);
        }
        return parent::__get($key);
    }
}
