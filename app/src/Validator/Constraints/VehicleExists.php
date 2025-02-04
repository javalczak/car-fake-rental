<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * CarExist constraint.
 */
#[\Attribute]
class VehicleExists extends Constraint
{
    public string $message = 'Vehicle with ID "{{ value }}" does not exist.';
}