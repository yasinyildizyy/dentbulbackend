<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constant\Entity\UserConstant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setFullName('Admin User')
            ->setEmail('admin@example.com')
            ->setPlainPassword('admin')
            ->setRole(UserConstant::ADMIN);
        $manager->persist($admin);
        $manager->flush();
    }
}
