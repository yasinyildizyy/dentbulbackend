<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class SettingFixtures extends Fixture
{
    public const SETTINGS = [
        'phoneNumber' => [
            [
                'key' => 'text',
                'value' => '0(850)0000000',
            ],
            [
                'key' => 'url',
                'value' => 'tel:08500000000',
            ],
        ],
        'email' => [
            [
                'key' => 'text',
                'value' => 'test@example.com',
            ],
            [
                'key' => 'url',
                'value' => 'mailto:test@example.com',
            ],
        ],
        'openClock' => [
            [
                'key' => 'openClock',
                'value' => '9.00 - 18.00',
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SETTINGS as $uniqueName => $extensions) {
            $object = (new Setting())->setUniqueName($uniqueName)->setExtensions($extensions);
            $manager->persist($object);
        }
        $manager->flush();
    }
}
