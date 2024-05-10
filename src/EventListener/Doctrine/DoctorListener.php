<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\Doctor;
use App\Utils\UploaderHelper;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class DoctorListener
{
    public function __construct(private readonly UploaderHelper $uploaderHelper)
    {
    }

    public function postLoad(Doctor $object): void
    {
        if ($object->getPhotoPath()) {
            $object->photoUrl = $this->uploaderHelper->asset($object, 'photoFile');
        }
    }
}
