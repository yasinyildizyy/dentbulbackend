<?php

declare(strict_types=1);

namespace App\Extension\Doctrine\Orm\Item;

use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class IsActiveItemExtension extends AbstractItemExtension
{
    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
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
