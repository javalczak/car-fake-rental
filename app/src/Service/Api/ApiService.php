<?php

namespace App\Service\Api;

use AllowDynamicProperties;
use App\Entity\Brand;
use App\Entity\Vehicle;
use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

#[AllowDynamicProperties] class ApiService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> em = $entityManager;
        $this -> vehicleRepo = $this -> em -> getRepository(Vehicle::class);
        $this -> brandRepo = $this -> em -> getRepository(Brand::class);
    }

    public function getVehicleArray(): array
    {
        $result = $this -> vehicleRepo -> createQueryBuilder('table')
            -> select('table')
            -> orderBy('table.id', 'DESC')
            -> getQuery()
            -> getResult();

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

}