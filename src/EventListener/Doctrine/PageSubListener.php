<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\PageSub;
use App\Utils\UploaderHelper;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class PageSubListener
{
    public function __construct(private readonly UploaderHelper $uploaderHelper)
    {
    }

    public function postLoad(PageSub $object): void
    {
        if ($object->getPhotoPath()) {
            $object->photoUrl = $this->uploaderHelper->asset($object, 'photoFile');
        }
    }
}
