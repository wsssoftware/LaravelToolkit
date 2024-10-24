<?php

namespace LaravelToolkit\Tests;

use Illuminate\Auth\Access\Response;
use LaravelToolkit\ACL\HasDenyResponse;

enum UserRole: string implements HasDenyResponse
{

    case ADMIN = 'admin';
    case USER = 'user';

    public function denyResponse(): Response
    {
        return match($this) {
            self::ADMIN => Response::denyWithStatus(403),
            self::USER => Response::denyAsNotFound(),
        };
    }
}
