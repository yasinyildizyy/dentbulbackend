<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Entity\MenuConstant;
use App\Constant\Locale;
use App\Entity\Menu;
use Bigoen\ApiPlatformTranslationBundle\Doctrine\Orm\Repository\Traits\TranslationRepositoryTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class MenuFixtures extends Fixture
{
    use TranslationRepositoryTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(Locale::TR);
        foreach (MenuConstant::getTypes() as $type) {
            for ($i = 1; $i <= rand(6, 8); ++$i) {
                $object = $this->new($faker, $type, null)->setPosition($i);
                $manager->persist($object);
                for ($j = 1; $j <= rand(1, 5); ++$j) {
                    $sub = $this->new($faker, $type, $object)->setPosition($j);
                    $manager->persist($sub);
                }
            }
        }
        $manager->flush();
    }

    private function new(Generator $faker, string $type, ?Menu $parent): Menu
    {
        $object = (new Menu())
            ->setName($faker->text(50))
            ->setPath($faker->slug())
            ->setType($type)
            ->setParent($parent);
        // translations.
        foreach (Locale::getAll() as $locale) {
            if (Locale::TR === $locale) {
                continue;
            }
            $this
                ->translate($object, 'name', $locale, "$locale {$object->getName()}")
                ->translate($object, 'path', $locale, "$locale-{$object->getPath()}");
        }

        return $object;
    }
}
