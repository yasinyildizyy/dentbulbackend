<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Locale;
use App\Entity\Faq;
use App\Entity\FaqGroup;
use Bigoen\ApiPlatformTranslationBundle\Doctrine\Orm\Repository\Traits\TranslationRepositoryTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class FaqGroupFixtures extends Fixture
{
    use TranslationRepositoryTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(Locale::TR);
        for ($i = 1; $i <= 4; ++$i) {
            $object = (new FaqGroup())
                ->setTitle($faker->text(100))
                ->setPosition($i);
            for ($j = 1; $j <= rand(4, 10); ++$j) {
                $sub = (new Faq())
                    ->setQuestion($faker->text(100))
                    ->setAnswer($faker->text(250))
                    ->setPosition($j);
                // translations.
                foreach (Locale::getAll() as $locale) {
                    if (Locale::TR === $locale) {
                        continue;
                    }
                    $this
                        ->translate($sub, 'question', $locale, "$locale {$sub->getQuestion()}")
                        ->translate($sub, 'answer', $locale, "$locale {$sub->getAnswer()}");
                }
                $object->addFaq($sub);
            }
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
