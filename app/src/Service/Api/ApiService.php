<?php

namespace App\Service\Api;

use AllowDynamicProperties;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Customer;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

#[AllowDynamicProperties] class ApiService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> em = $entityManager;
        $this -> vehicleRepo = $this -> em -> getRepository(Vehicle::class);
        $this -> brandRepo = $this -> em -> getRepository(Brand::class);
        $this -> cityRepo = $this -> em -> getRepository(City::class);
    }

    public function getVehicleArray(): array
    {
        $result = $this -> vehicleRepo -> createQueryBuilder('table')
            -> select('table')
            -> orderBy('table.id', 'DESC')
            -> getQuery()
            -> getResult();

        $vehicleArray = [];
        foreach ($result as $item) {
            $vehicleArray[] = [
                'id' => $item -> getId(),
                'brand' => $item -> getBrand() -> getName(),
                'type' => $item -> getType(),
                'fuelType' => $item -> getFuel() -> getFuel(),
                'vin' => $item -> getVin(),
                'plate' => $item -> getPlate(),
                'inMaintenance' => $item -> isMaintenance(),
                'description' => $item -> getDescription(),
                'inUse' => 1
            ];
        }

        return $vehicleArray;
    }

    public function addCustomer($fullName, $address, $idNumber, $cityId): int
    {
        $newCustomer = new Customer();
        $newCustomer -> setFullName(trim($fullName));
        $newCustomer -> setAddress(trim($address));
        $newCustomer -> setIdNumber(trim($idNumber));
        $newCustomer -> setCity($this -> cityRepo -> find($cityId));
        $newCustomer -> setAddedAt(new DateTime('now'));

        $this -> em -> persist($newCustomer);
        $this -> em -> flush();

        return $newCustomer -> getId();
    }
}
