<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Locale;
use App\Entity\BlogPost;
use Bigoen\ApiPlatformTranslationBundle\Doctrine\Orm\Repository\Traits\TranslationRepositoryTrait;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BlogPostFixtures extends Fixture implements StaticFixturesInterface
{
    use TranslationRepositoryTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(Locale::TR);
        for ($i = 1; $i <= 10; ++$i) {
            $object = $this->new($faker, null);
            if ($i % 3 === 0) {
                $object->setIsActive(false);
            }
            $manager->persist($object);
            for ($j = 1; $j <= 4; ++$j) {
                $sub = $this->new($faker, $object);
                if ($j % 3 === 0) {
                    $sub->setIsActive(false);
                }
                $manager->persist($sub);
            }
        }
        $manager->flush();
    }

    private function new(Generator $faker, ?BlogPost $parent): BlogPost
    {
        $object = (new BlogPost())
            ->setTitle($faker->text(100))
            ->setDescription($faker->text())
            ->setBody($faker->text(500))
            ->setPhotoPath(self::PHOTO_PATH)
            ->setWriteAt((new DateTimeImmutable())->modify(sprintf('-%d days', rand(1, 20))));
        if ($parent) {
            $object->setParent($parent);
        }
        // translations.
        foreach (Locale::getAll() as $locale) {
            if (Locale::TR === $locale) {
                continue;
            }
            $this
                ->translate($object, 'title', $locale, "$locale {$object->getTitle()}")
                ->translate($object, 'description', $locale, "$locale {$object->getDescription()}")
                ->translate($object, 'body', $locale, "$locale {$object->getBody()}");
        }

        return $object;
    }
}
