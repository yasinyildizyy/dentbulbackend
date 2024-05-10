<?php

declare(strict_types=1);

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class LatLng extends Constraint
{
    public string $message = 'latLng.error';
}
