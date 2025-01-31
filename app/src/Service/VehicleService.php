<?php

namespace App\Service;

use App\Entity\Brand;
use App\Entity\Vehicle;
use Exception;
use InvalidArgumentException;

class VehicleService extends AbstractService
{
    public function getFuelTypeArray(): array | null
    {
        $result = $this -> fuelTypeRepo -> createQueryBuilder('table')
            -> select('table')
            -> orderBy('table.id', 'ASC')
            -> getQuery()
            -> getResult();

        foreach ($result as $item) {
            $fuelTypeArray[] = [
                'id' => $item -> getId(),
                'fuelType' => $item -> getFuel()
            ];
        }

        return $fuelTypeArray ?? null;
    }

    public function addNewVehicle($brandId, $type, $fuelTypeId, $description, $vin, $plate, $maintenance): void
    {
        // Walidacja
        if (empty($brandId) || empty($type) || empty($fuelTypeId) || empty($vin) || empty($plate)) {
            throw new InvalidArgumentException('Wszystkie wymagane pola (Marka, Model, Paliwo, VIN, Rejestracja) muszą być wypełnione.');
        }

        $newVehicle = new Vehicle();
        $newVehicle -> setBrand($this -> getBrandObject($brandId));
        $newVehicle -> setType(trim($type));
        $newVehicle -> setFuel($this -> getFuelTypeObject($fuelTypeId));
        $newVehicle -> setDescription(trim($description));
        $newVehicle -> setVin(trim($vin));
        $newVehicle -> setPlate(trim($plate));
        $newVehicle -> setMaintenance($maintenance);

        $this -> save($newVehicle);
    }

    public function doesBrandExists($brand): bool
    {
        $result = $this -> brandRepo -> createQueryBuilder('table')
            -> select('table')
            -> where('table.name LIKE :brand')
            -> setParameter('brand', $brand)
            -> getQuery()
            -> getResult();

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function addNewBrand($brand): true
    {
        $brandEntity = new Brand();
        $brandEntity -> setName($brand);
        $this -> save($brandEntity);

        return true;
    }

    /**
     * @throws Exception
     */
    public function getBrandArray(): ? array
    {
        $result = $this -> brandRepo -> createQueryBuilder('table')
            -> select('table')
            -> orderBy('table.id', 'ASC')
            -> getQuery()
            -> getResult();

        if (empty($result)) {
            throw new Exception('Wynik jest pusty!');
        }

        foreach ($result as $item) {
            $brandArray[] = [
                'id' => $item -> getId(),
                'name' => $item -> getName()
            ];
        }

        return $brandArray ?? null;
    }
}