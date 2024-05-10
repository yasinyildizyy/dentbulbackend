<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController as Base;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
abstract class AbstractCrudController extends Base
{
    public function __construct(
        protected readonly RequestStack $requestStack,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly TranslatorInterface $translator
    ) {
    }

    public function getUserId(): ?int
    {
        $user = $this->getUser();

        return method_exists($user, 'getId') ? $user->getId() : null;
    }

    protected function hasRequestParameter(string $key): bool
    {
        return null !== $this->getRequestParameter($key);
    }

    protected function getRequestParameter(string $key): ?string
    {
        $request = $this->getRequest();
        if (!$request instanceof Request) {
            return null;
        }
        $parameter = $request->get($key);

        return \is_string($parameter) ? $parameter : null;
    }

    protected function isSameRequestParameter(string $key, $value): bool
    {
        return $value === $this->getRequestParameter($key);
    }

    protected function getRequest(): ?Request
    {
        return $this->requestStack->getMainRequest() ?? $this->requestStack->getCurrentRequest();
    }

    protected function findOneByMainId(string $mainClass): ?object
    {
        if (!($mainId = $this->getRequestParameter('mainId'))) {
            return null;
        }

        return $this->entityManager->getRepository($mainClass)->find($mainId);
    }
}
