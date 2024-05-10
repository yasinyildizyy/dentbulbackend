<?php

declare(strict_types=1);

namespace App\Constant\Entity;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class UserConstant
{
    final public const ADMIN = 'ROLE_ADMIN';

    public static function getRoles(): array
    {
        return [
            self::ADMIN,
        ];
    }

    public static function getRoleChoices(): array
    {
        return [
            'choice.user.role.admin' => self::ADMIN,
        ];
    }
}
