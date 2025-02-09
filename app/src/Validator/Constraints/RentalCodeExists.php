<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * RentalCodeExists constraint.
 */
#[\Attribute]
class RentalCodeExists extends Constraint
{
    public string $message = 'Rental code does not exist.';
}