<?php

namespace App\Service;

use AllowDynamicProperties;
use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Customer;
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
        $this -> cityRepo = $this -> em -> getRepository(City::class);
        $this -> customerRepo = $this -> em -> getRepository(Customer::class);
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

    public function getFuelTypeObject($fuelTypeId = 789)
    {
        return $this -> fuelTypeRepo -> find($fuelTypeId);
    }

    public function getVehicleObject($vehicleId)
    {
        return $this -> vehicleRepo -> find($vehicleId);
    }

    public function getCityObject($cityId)
    {
        return $this -> cityRepo -> find($cityId);
    }

    public function getCustomerObject($customerId)
    {
        return $this -> customerRepo -> find($customerId);
    }
}