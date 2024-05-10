<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\Gallery;
use App\Utils\UploaderHelper;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class GalleryListener
{
    public function __construct(private readonly UploaderHelper $uploaderHelper)
    {
    }

    public function postLoad(Gallery $object): void
    {
        if ($object->getPath()) {
            $object->url = $this->uploaderHelper->asset($object, 'file');
        }
    }
}
