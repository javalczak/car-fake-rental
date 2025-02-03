<?php

// src/Validator/Constraints/CityExistsValidator.php

namespace App\Validator\Constraints;

use App\Repository\CityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CityExistsValidator extends ConstraintValidator
{
    private $cityRepo;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepo = $cityRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CityExists) {
            throw new UnexpectedTypeException($constraint, CityExists::class);
        }

        // is int?
        if (!is_numeric($value)) {
            $this -> context -> buildViolation('City ID must be numeric.')
                -> addViolation();
            return;
        }

        // city exist in schema?
        $city = $this -> cityRepo -> find($value);
        if (!$city) {
            $this -> context -> buildViolation($constraint -> message)
                -> setParameter('{{ value }}', $value)
                -> addViolation();
        }
    }
}
