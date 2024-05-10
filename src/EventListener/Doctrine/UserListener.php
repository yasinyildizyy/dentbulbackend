<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\User;
use App\EventListener\Doctrine\Traits\LifecycleEventArgsTrait;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 *
 * @property User $object
 */
class UserListener
{
    use LifecycleEventArgsTrait;

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function prePersist(User $object, LifecycleEventArgs $args): void
    {
        $this->setArgs($object, $args)->hashPassword();
    }

    public function preUpdate(User $object, PreUpdateEventArgs $args): void
    {
        $this->setArgs($object, $args)->hashPassword();
    }

    private function hashPassword(): self
    {
        if (null === $this->object->getPlainPassword()) {
            return $this;
        }
        $password = $this->passwordHasher->hashPassword($this->object, $this->object->getPlainPassword());
        $this->object->setPassword($password);

        return $this;
    }
}
