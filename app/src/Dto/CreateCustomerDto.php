<?php

namespace App\Dto;

use App\Validator\Constraints\CityExists;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCustomerDto
{
    #[Assert\NotBlank(message: 'Full name is required.')]
    public string $fullName;

    #[Assert\NotBlank(message: 'Address is required.')]
    public string $address;

    #[Assert\NotBlank(message: 'City ID is required.')]
    #[Assert\Type(type: 'numeric', message: 'City ID must be a number.')]
    #[CityExists]
    public int $cityId;
}