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
        $this -> customerRepo = $this -> em -> getRepository(Customer::class);
    }

    public function whichUserUsesAVehicle($vehicleId): string | null
    {
        $vehicle = $this -> vehicleRepo -> find($vehicleId);
        $customer = $this -> customerRepo -> findOneBy(['uses' => $vehicle]);

        return $customer ?-> getId();
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
                'inMaintenance' => $item -> getMaintenance(),
                'description' => $item -> getDescription(),
                'inUse' => $this -> whichUserUsesAVehicle($item -> getId())
            ];
        }

        return $vehicleArray;
    }

    public function doesVehicleIsInUse($vehicleId): bool
    {
        $vehicle = $this -> vehicleRepo -> find($vehicleId);
        $result = $this -> customerRepo -> findBy(['uses' => $vehicle]);

        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

    public function canWeReleaseVehicle($vehicleId, $rentalCode): bool
    {
        $customerId = $this -> getCustomerIdViaRentalCode($rentalCode);
        $vehicle = $this -> vehicleRepo -> find($vehicleId);
        $result = $this -> customerRepo -> findBy(['uses' => $vehicle, 'id' => $customerId]);

        if (!isset($result[0])) {
            return false;
        } else {
            return true;
        }
    }

    public function customerExists($fullName)
    {
        return $this -> customerRepo -> createQueryBuilder('table')
            -> select('table')
            -> where ('table.fullName LIKE :fullName')
            -> setParameter('fullName', $fullName)
            -> getQuery()
            -> getResult();
    }

    public function addCustomer($fullName, $address, $cityId, $rentalCode): string
    {
        $newCustomer = new Customer();
        $newCustomer -> setFullName(trim($fullName));
        $newCustomer -> setAddress(trim($address));
        $newCustomer -> setIdNumber($rentalCode);
        $newCustomer -> setCity($this -> cityRepo -> find($cityId));
        $newCustomer -> setAddedAt(new DateTime('now'));

        $this -> em -> persist($newCustomer);
        $this -> em -> flush();

       return $newCustomer -> getIdNumber();
    }

    public function getCityArray(): array
    {
        $citiArray = [];
        foreach ( $this -> cityRepo -> findAll() as $item) {

            $citiArray[] = [
                'id' => $item -> getId(),
                'name' => $item -> getName()
            ];
        }

        return $citiArray;
    }

    public function generateUniqueCode(): string
    {
        return substr(str_shuffle(str_repeat(
            '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', 6
        )), 0, 6);
    }

    public function doesFullNameExists($fullName)
    {
        $result = $this -> customerRepo -> createQueryBuilder('table')
            -> select('table')
            -> where('table.fullName = :fullName')
            -> setParameter('fullName', $fullName)

            -> getQuery()
            -> getResult();

        if ([] == $result) {
            return null;
        } else {
           return $result[0] -> getIdNumber();
        }
    }

    public function getCustomerIdViaRentalCode($rentalCode)
    {
        $result = $this -> customerRepo -> findBy(['idNumber' => $rentalCode]);

        return $result[0] -> getId();
    }

    public function rentVehicle($vehicleId, $rentalCode): void
    {
        // find customer id via rental code
        $customerId = $this -> getCustomerIdViaRentalCode($rentalCode);

        $customer = $this -> customerRepo -> find($customerId);
        $customer -> setUses($this -> vehicleRepo -> find($vehicleId));

        $this -> em -> persist($customer);
        $this -> em -> flush();
    }

    public function releaseVehicle($vehicleId): void
    {
        $vehicle = $this -> vehicleRepo -> find($vehicleId);
        $customer = $this -> customerRepo -> findOneBy(['uses' => $vehicle]);
        $customer -> setUses(null);

        $this -> em -> persist($customer);
        $this-> em -> flush();
    }
}
