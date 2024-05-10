<?php

declare(strict_types=1);

namespace App\Entity;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
abstract class AbstractEntity
{
    use SoftDeleteableEntity;
    use TimestampableEntity;
}
