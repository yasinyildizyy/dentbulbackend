<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\CureSub;
use App\Utils\UploaderHelper;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class CureSubListener
{
    public function __construct(private readonly UploaderHelper $uploaderHelper)
    {
    }

    public function postLoad(CureSub $object): void
    {
        if ($object->getPhotoPath()) {
            $object->photoUrl = $this->uploaderHelper->asset($object, 'photoFile');
        }
    }
}
