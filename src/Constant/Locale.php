<?php

declare(strict_types=1);

namespace App\Constant;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class Locale
{
    public const DE = 'de';
    public const EN = 'en';
    public const FR = 'fr';
    public const TR = 'tr';
    public const RU = 'ru';
    public const LOCALES = [
        self::DE,
        self::EN,
        self::FR,
        self::TR,
        self::RU,
    ];

    public static function getAll(): array
    {
        return self::LOCALES;
    }

    public static function getChoices(): array
    {
        $choices = [];
        foreach (self::LOCALES as $locale) {
            $choices["choice.locale.$locale"] = $locale;
        }

        return $choices;
    }
}
