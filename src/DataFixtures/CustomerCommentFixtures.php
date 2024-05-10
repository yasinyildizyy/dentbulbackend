<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Locale;
use App\Entity\CustomerComment;
use App\Entity\CustomerCommentPhoto;
use Bigoen\ApiPlatformTranslationBundle\Doctrine\Orm\Repository\Traits\TranslationRepositoryTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class CustomerCommentFixtures extends Fixture implements StaticFixturesInterface
{
    use TranslationRepositoryTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(Locale::TR);
        for ($i = 1; $i <= 15; ++$i) {
            $object = (new CustomerComment())
                ->setFullName($faker->name())
                ->setComment($faker->text(250))
                ->setVideoId(self::VIDEO_ID)
                ->setYear(rand(2000, 2022))
                ->setIsShowHomepage($i % 7 !== 0)
                ->setIsActive($i % 7 !== 0)
                ->setCountryName($faker->country())
                ->setType($faker->text(10));
            // translations.
            foreach (Locale::getAll() as $locale) {
                if (Locale::TR === $locale) {
                    continue;
                }
                $this
                    ->translate($object, 'comment', $locale, "$locale {$object->getComment()}")
                    ->translate($object, 'countryName', $locale, "$locale {$object->getCountryName()}")
                    ->translate($object, 'type', $locale, "$locale {$object->getType()}");
            }
            $manager->persist($object);
            for ($j = 1; $j <= rand(1, 3); ++$j) {
                $photo = (new CustomerCommentPhoto())
                    ->setCustomerComment($object)
                    ->setPath(self::PHOTO_PATH);
                $manager->persist($photo);
            }
        }
        $manager->flush();
    }
}
