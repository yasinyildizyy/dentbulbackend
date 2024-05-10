<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\FaqGroup;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class FaqGroupListener
{
    public function preRemove(FaqGroup $object, LifecycleEventArgs $args): void
    {
        $objectManager = $args->getObjectManager();
        foreach ($object->getCures() as $cure) {
            $cure->setFaqGroup(null);
            $objectManager->persist($cure);
        }
        $objectManager->flush();
    }
}
