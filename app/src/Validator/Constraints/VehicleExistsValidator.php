<?php

namespace App\Validator\Constraints;

use AllowDynamicProperties;
use App\Repository\CityRepository;
use App\Repository\VehicleRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[AllowDynamicProperties] class VehicleExistsValidator extends ConstraintValidator
{
    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this -> vehicleRepo = $vehicleRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof VehicleExists) {
            throw new UnexpectedTypeException($constraint, VehicleExists::class);
        }

        // is int?
        if (!is_numeric($value)) {
            $this -> context -> buildViolation('Vehicle ID must be numeric.')
                -> addViolation();
            return;
        }

        // city exist in schema?
        $vehicle = $this -> vehicleRepo -> find($value);
        if (!$vehicle) {
            $this -> context -> buildViolation($constraint -> message)
                -> setParameter('{{ value }}', $value)
                -> addViolation();
        }
    }
}
