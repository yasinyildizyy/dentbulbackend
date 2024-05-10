<?php

declare(strict_types=1);

namespace App\Constant\Entity;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class MenuConstant
{
    final public const HEADER = 'header';
    final public const FOOTER = 'footer';

    public static function getTypes(): array
    {
        return [
            self::HEADER,
            self::FOOTER,
        ];
    }

    public static function getTypeChoices(): array
    {
        return [
            'choice.menu.type.header' => self::HEADER,
            'choice.menu.type.footer' => self::FOOTER,
        ];
    }
}
