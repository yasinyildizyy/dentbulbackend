<?php

declare(strict_types=1);

namespace App\Extension;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
interface SupportsApplyInterface
{
    public function supports(string $resourceClass): bool;
}
