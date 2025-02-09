<?php

namespace App\Validator\Constraints;

use AllowDynamicProperties;
use App\Repository\CustomerRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[AllowDynamicProperties] class RentalCodeExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly CustomerRepository $customerRepository
    ) {}

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof RentalCodeExists) {
            throw new UnexpectedTypeException($constraint, RentalCodeExists::class);
        }

        // rental exists in schema?
        $result = $this -> customerRepository -> findBy(['idNumber' => $value]);

        if (empty($result)) {
            $this -> context -> buildViolation($constraint -> message)
                -> setParameter('{{ value }}', $value)
                -> addViolation();
        }
    }
}
