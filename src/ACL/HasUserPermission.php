<?php

namespace LaravelToolkit\ACL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use LaravelToolkit\Facades\ACL;

/**
 * @property \LaravelToolkit\ACL\UserPermission $userPermission
 */
trait HasUserPermission
{
    public static function bootHasUserPermission()
    {
        self::retrieved(function (Model $model) {
            /** @var \LaravelToolkit\ACL\HasUserPermission $model */
            if (ACL::model() !== null && empty($model->userPermission)) {
                $model->userPermission()->create(['roles' => collect()]);
                $model->refresh();
            }
        });
    }

    public function userPermission(): HasOne
    {
        return $this->hasOne(ACL::model() ?? UserPermission::class, 'id', 'id');
    }
}
