<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\CustomerCommentPhoto;
use App\Utils\UploaderHelper;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class CustomerCommentPhotoListener
{
    public function __construct(private readonly UploaderHelper $uploaderHelper)
    {
    }

    public function postLoad(CustomerCommentPhoto $object): void
    {
        if ($object->getPath()) {
            $object->url = $this->uploaderHelper->asset($object, 'file');
        }
    }
}
