<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Locale;
use App\Entity\Page;
use App\Entity\PageSub;
use Bigoen\ApiPlatformTranslationBundle\Doctrine\Orm\Repository\Traits\TranslationRepositoryTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class PageFixtures extends Fixture implements StaticFixturesInterface
{
    use TranslationRepositoryTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(Locale::TR);
        for ($i = 1; $i <= 10; ++$i) {
            $object = (new Page())
                ->setTitle($faker->text(100))
                ->setDescription($faker->text(500))
                ->setVideoId(self::VIDEO_ID)
                ->setPhotoPath(self::PHOTO_PATH);
            // translations.
            foreach (Locale::getAll() as $locale) {
                if (Locale::TR === $locale) {
                    continue;
                }
                $this
                    ->translate($object, 'title', $locale, "$locale {$object->getTitle()}")
                    ->translate($object, 'description', $locale, "$locale {$object->getDescription()}");
            }
            for ($j = 1; $j <= rand(1, 7); ++$j) {
                $sub = (new PageSub())
                    ->setTitle($faker->text(100))
                    ->setDescription($faker->text(250))
                    ->setPhotoPath(self::PHOTO_PATH);
                // translations.
                foreach (Locale::getAll() as $locale) {
                    if (Locale::TR === $locale) {
                        continue;
                    }
                    $this
                        ->translate($sub, 'title', $locale, "$locale {$sub->getTitle()}")
                        ->translate($sub, 'description', $locale, "$locale {$sub->getDescription()}");
                }
                $object->addSub($sub);
            }
            $manager->persist($object);
        }
        $manager->flush();
    }
}
