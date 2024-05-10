<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class LatLngValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof LatLng) {
            throw new UnexpectedTypeException($constraint, LatLng::class);
        }
        $lat = (string) $value['lat'];
        $lng = (string) $value['lng'];
        if (!preg_match('/^[0-9\-\.]+$/', $lat, $matches) || !preg_match('/^[0-9\-\.]+$/', $lng, $matches)) {
            $this->context->buildViolation($constraint->message)->addViolation();

            return;
        }
        if ($lat > 90 || $lat < -90 || $lng > 180 || $lng < -180) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
