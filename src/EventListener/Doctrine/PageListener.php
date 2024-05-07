<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\Page;
use App\Utils\UploaderHelper;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class PageListener
{
    public function __construct(private readonly UploaderHelper $uploaderHelper)
    {
    }

    public function postLoad(Page $object): void
    {
        if ($object->getPhotoPath()) {
            $object->photoUrl = $this->uploaderHelper->asset($object, 'photoFile');
        }
    }
}
