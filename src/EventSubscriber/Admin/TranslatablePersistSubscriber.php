<?php

declare(strict_types=1);

namespace App\EventSubscriber\Admin;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class TranslatablePersistSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[ArrayShape([AfterEntityPersistedEvent::class => "string[]"])]
    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => ['persist'],
        ];
    }

    /**
     * Todo: Temporary create persist. Details for please reading bigoen/gedmo-translation-form-bundle.
     */
    public function persist(AfterEntityPersistedEvent $event): void
    {
        $object = $event->getEntityInstance();
        // has vich uploadable.
        $reflection = new ReflectionClass($object);
        if (0 === count($reflection->getAttributes(Uploadable::class))) {
            return;
        }
        // persist again for upload file.
        $object->setUpdatedAt(new DateTime());
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
