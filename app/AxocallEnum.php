<?php

namespace App;

enum AxocallEnum
{
    case USER_STATUS_ACTIVE;
    case USER_STATUS_INACTIVE;
    case USER_STATUS_BLOCKED;

    public function label(): string
    {
        return match ($this) {
            self::USER_STATUS_ACTIVE => 'active',
            self::USER_STATUS_INACTIVE => 'inactive',
            self::USER_STATUS_BLOCKED => 'blocked',
        };
    }
}