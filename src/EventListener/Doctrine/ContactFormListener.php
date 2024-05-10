<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\ContactForm;
use App\WebService\Doktor365Service;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ContactFormListener
{
    public function __construct(private readonly Doktor365Service $doktor365Service, private readonly string $branchEnv)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function postPersist(ContactForm $object): void
    {
        if ('prod' === $this->branchEnv && ('empty@dentbul.com' !== $object->getEmail() || 'empty' !== $object->getPhoneNumber())) {
            $this->doktor365Service->post($object);
        }
    }
}
