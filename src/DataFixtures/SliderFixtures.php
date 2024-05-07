<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Locale;
use App\Entity\Slider;
use Bigoen\ApiPlatformTranslationBundle\Doctrine\Orm\Repository\Traits\TranslationRepositoryTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class SliderFixtures extends Fixture implements StaticFixturesInterface
{
    use TranslationRepositoryTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(Locale::TR);
        for ($i = 1; $i <= 8; $i++) {
            $object = (new Slider())
                ->setTitle($faker->text(60))
                ->setDescription($faker->text(180))
                ->setButtonName(0 === $i % 3 ? null : $faker->text(50))
                ->setButtonUrl(0 === $i % 3 ? null : $faker->url())
                ->setPhotoPath(self::PHOTO_PATH)
                ->setPosition($i)
                ->setIsActive(0 !== $i % 7);
            // translations.
            foreach (Locale::getAll() as $locale) {
                if (Locale::TR === $locale) {
                    continue;
                }
                $this
                    ->translate($object, 'title', $locale, "$locale {$object->getTitle()}")
                    ->translate($object, 'description', $locale, "$locale {$object->getDescription()}")
                    ->translate($object, 'buttonName', $locale, "$locale {$object->getButtonName()}")
                    ->translate($object, 'buttonUrl', $locale, "$locale {$object->getButtonUrl()}");
            }
            $manager->persist($object);
        }
        $manager->flush();
    }
}
