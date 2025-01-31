<?php

namespace App\Service;

use AllowDynamicProperties;
use App\Entity\Brand;
use App\Entity\FuelType;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;

#[AllowDynamicProperties] abstract class AbstractService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> em = $entityManager;
        $this -> vehicleRepo = $this -> em -> getRepository(Vehicle::class);
        $this -> fuelTypeRepo = $this -> em -> getRepository(FuelType::class);
        $this -> brandRepo = $this -> em -> getRepository(Brand::class);
    }

    public function save($object)
    {
        $this -> em -> persist($object);
        $this -> em -> flush();

        return $object;
    }

    public function delete($object)
    {
        $this -> em -> remove($object);
        $this -> em -> flush();

        return $object;
    }

    public function getBrandObject($brandId)
    {
        return $this -> brandRepo -> find($brandId);
    }

    public function getFuelTypeObject($fuelTypeId)
    {
        return $this -> fuelTypeRepo -> find($fuelTypeId);
    }

    public function getVehicleObject($vehicleId)
    {
        return $this -> vehicleRepo -> find($vehicleId);
    }
}