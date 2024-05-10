<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Locale;
use App\Entity\Doctor;
use Bigoen\ApiPlatformTranslationBundle\Doctrine\Orm\Repository\Traits\TranslationRepositoryTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class DoctorFixtures extends Fixture implements StaticFixturesInterface
{
    use TranslationRepositoryTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(Locale::TR);
        for ($i = 1; $i <= 10; ++$i) {
            $object = (new Doctor())
                ->setFullName($faker->name())
                ->setTitle($faker->title())
                ->setPhotoPath(self::PHOTO_PATH)
                ->setPosition($i)
                ->setIsActive($i % 7 !== 0);
            // translations.
            foreach (Locale::getAll() as $locale) {
                if (Locale::TR === $locale) {
                    continue;
                }
                $this->translate($object, 'title', $locale, "$locale {$object->getTitle()}");
            }
            $manager->persist($object);
        }
        $manager->flush();
    }
}
