<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Locale;
use App\Entity\Cure;
use App\Entity\CureSub;
use App\Entity\FaqGroup;
use Bigoen\ApiPlatformTranslationBundle\Doctrine\Orm\Repository\Traits\TranslationRepositoryTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class CureFixtures extends Fixture implements DependentFixtureInterface, StaticFixturesInterface
{
    use TranslationRepositoryTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(Locale::TR);
        $faqGroups = $manager->getRepository(FaqGroup::class)->findAll();
        for ($i = 1; $i <= 15; ++$i) {
            $object = (new Cure())
                ->setName($faker->text(75))
                ->setShortDescription($faker->text())
                ->setLongDescription($faker->text(500))
                ->setVideoId(self::VIDEO_ID)
                ->setPhotoPath(self::PHOTO_PATH)
                ->setIsShowHomepage($i % 3 === 0)
                ->setIsActive($i % 5 !== 0)
                ->setFaqGroup($faqGroups[array_rand($faqGroups)]);
            // translations.
            foreach (Locale::getAll() as $locale) {
                if (Locale::TR === $locale) {
                    continue;
                }
                $this
                    ->translate($object, 'name', $locale, "$locale {$object->getName()}")
                    ->translate($object, 'shortDescription', $locale, "$locale {$object->getShortDescription()}")
                    ->translate($object, 'longDescription', $locale, "$locale {$object->getLongDescription()}");
            }
            for ($j = 1; $j <= rand(1, 6); ++$j) {
                $sub = (new CureSub())
                    ->setTitle($faker->text(60))
                    ->setDescription($faker->text())
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

    public function getDependencies(): array
    {
        return [
            FaqGroupFixtures::class,
        ];
    }
}
