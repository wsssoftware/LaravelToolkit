<?php

namespace LaravelToolkit\ACL;

use Illuminate\Database\Eloquent\Relations\HasOne;
use LaravelToolkit\Facades\ACL;

/**
 * @property \LaravelToolkit\ACL\UserPermission $userPermission
 */
trait HasUserPermission
{
    public function userPermission(): HasOne
    {
        $relation = $this->hasOne(ACL::model() ?? UserPermission::class, 'id', 'id');
        if ($relation->doesntExist()) {
            $relation->create(['roles' => collect()]);
            $this->refresh();
        }

        return $relation;
    }
}
