<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\SocialMediaAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class SocialMediaAccountFixtures extends Fixture
{
    public const ICON_NAMES = [
        'instagram',
        'facebook',
        'whatsapp',
        'linkedin',
        'twitter',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach (self::ICON_NAMES as $key => $iconName) {
            $object = (new SocialMediaAccount())
                ->setUsername($faker->userName())
                ->setIconName($iconName)
                ->setUrl($faker->url())
                ->setPosition($key)
                ->setIsActive(0 !== $key % 3);
            $manager->persist($object);
        }
        $manager->flush();
    }
}
