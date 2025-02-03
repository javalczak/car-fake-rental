<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * CityExists constraint.
 */
#[\Attribute]
class CityExists extends Constraint
{
    public string $message = 'City with ID "{{ value }}" does not exist.';
}