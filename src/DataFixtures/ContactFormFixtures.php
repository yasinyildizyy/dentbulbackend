<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Locale;
use App\Entity\ContactForm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ContactFormFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (Locale::getAll() as $locale) {
            $faker = Factory::create($locale);
            for ($i = 1; $i <= 10; ++$i) {
                $object = (new ContactForm())
                    ->setLocale($locale)
                    ->setFullName($faker->name())
                    ->setEmail($faker->email())
                    ->setPhoneNumber($faker->phoneNumber())
                    ->setSubject($faker->text(50))
                    ->setMessage($faker->text(100))
                    ->setExtensions([
                        [
                            'title' => $faker->text(50),
                            'value' => $faker->text(100),
                        ],
                        [
                            'title' => $faker->text(50),
                            'value' => $faker->text(100),
                        ],
                        [
                            'title' => $faker->text(50),
                            'value' => $faker->text(100),
                        ],
                    ]);
                $manager->persist($object);
            }
        }
        $manager->flush();
    }
}
