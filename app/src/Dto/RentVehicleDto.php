<?php

namespace App\Dto;

use App\Validator\Constraints\RentalCodeExists;
use App\Validator\Constraints\VehicleExists;
use Symfony\Component\Validator\Constraints as Assert;

class RentVehicleDto
{
    #[Assert\NotBlank(message: 'Rental code is required')]
    #[RentalCodeExists]
    public string $rentalCode;

    #[Assert\NotBlank(message: 'Vehicle ID is required.')]
    #[Assert\Type(type: 'numeric', message: 'Vehicle ID must be a number.')]
    #[VehicleExists]
    public int $vehicleId;
}