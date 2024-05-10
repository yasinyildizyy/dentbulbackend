<?php

declare(strict_types=1);

namespace App\Extension\Doctrine\Orm\Collection;

use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class IsActiveCollectionExtension extends AbstractCollectionExtension
{
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {
        if (false === $this->supports($resourceClass)) {
            return;
        }
        $this->createQueryBuilder($queryBuilder)->andWhere("$this->rootAlias.isActive = true");
    }

    public function supports(string $resourceClass): bool
    {
        return property_exists($resourceClass, 'isActive');
    }
}
