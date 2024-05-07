<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Locale;
use App\Entity\Gallery;
use App\Entity\GalleryCategory;
use Bigoen\ApiPlatformTranslationBundle\Doctrine\Orm\Repository\Traits\TranslationRepositoryTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class GalleryFixtures extends Fixture implements StaticFixturesInterface
{
    use TranslationRepositoryTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(Locale::TR);
        for ($i = 1; $i <= 5; ++$i) {
            $object = (new GalleryCategory())
                ->setName($faker->text(100))
                ->setIsActive($i % 3 !== 0);
            // translations.
            foreach (Locale::getAll() as $locale) {
                if (Locale::TR === $locale) {
                    continue;
                }
                $this->translate($object, 'name', $locale, "$locale {$object->getName()}");
            }
            $manager->persist($object);
            for ($j = 1; $j <= 15; ++$j) {
                $gallery = (new Gallery())
                    ->setPath(self::PHOTO_PATH)
                    ->setCategory($object)
                    ->setPosition($j)
                    ->setIsActive($j % 7 !== 0);
                $manager->persist($gallery);
            }
        }
        $manager->flush();
    }
}
