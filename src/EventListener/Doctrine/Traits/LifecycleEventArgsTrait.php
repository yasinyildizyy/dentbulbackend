<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
trait LifecycleEventArgsTrait
{
    protected object $object;
    protected array $objectChangeSet = [];
    protected EntityManagerInterface|ObjectManager $objectManager;

    public function setArgs(?object $object, LifecycleEventArgs $args): self
    {
        $this->object = $object ?? $args->getObject();
        $this->objectManager = $args->getObjectManager();
        if ($args instanceof PreUpdateEventArgs) {
            $this->objectChangeSet = $args->getEntityChangeSet();
        }

        return $this;
    }
}
