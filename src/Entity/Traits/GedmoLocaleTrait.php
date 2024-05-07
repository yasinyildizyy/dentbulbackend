<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Constant\Locale;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
trait GedmoLocaleTrait
{
    #[Gedmo\Locale]
    protected ?string $locale = Locale::TR;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
}
