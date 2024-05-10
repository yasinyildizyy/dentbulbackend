<?php

declare(strict_types=1);

namespace App\Extension\Doctrine\Orm\Item;

use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use App\Extension\SupportsApplyInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
abstract class AbstractItemExtension implements QueryItemExtensionInterface, SupportsApplyInterface
{
    protected QueryBuilder $queryBuilder;
    protected ?string $rootAlias = null;

    public function createQueryBuilder(QueryBuilder $queryBuilder): QueryBuilder
    {
        $this->queryBuilder = $queryBuilder;
        $this->rootAlias ??= $this->queryBuilder->getRootAliases()[0];

        return $queryBuilder;
    }
}
