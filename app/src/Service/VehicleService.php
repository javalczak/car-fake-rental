<?php

namespace App\Service;

use App\Entity\Brand;
use App\Entity\Vehicle;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

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

        $brandArray = [];
        foreach ($result as $item) {
            $brandArray[] = [
                'id' => $item -> getId(),
                'name' => $item -> getName()
            ];
        }

        return $brandArray;
    }

    public function getVehicleArray(): array
    {
        $result = $this -> vehicleRepo -> createQueryBuilder('table')
            -> select('table')
            -> orderBy('table.id', 'DESC')
            -> getQuery()
            -> getResult();

        $vehicleArray = [];
        /** @var Vehicle $item */
        foreach ($result as $item) {
            $vehicleArray[] = [
                'id' => $item -> getId(),
                'brand' => $item -> getBrand() -> getName(),
                'type' => $item -> getType(),
                'maintenance' => $item -> isMaintenance(),
                'plate' => $item -> getPlate(),
                'description' => $item -> getDescription(),
                'fuel' => $item -> getFuel() -> getFuel()
            ];
        }

        return $vehicleArray;
    }

    /**
     * @throws Exception
     */
    public function doesVehicleCanBeDeleted($vehicleId): bool
    {
        // sprawdzamy, czy taki vehicle istnieje
        if (false === $this -> vehicleRepo -> find($vehicleId)) {
            throw new Exception('Nie mamy takiej fury na stanie');
        }

        // sprawdzamy, czy jest w użyciu
        // TODO: later

        return true;
    }

    public function deleteVehicle($vehicleId): void
    {
        $result = $this -> getVehicleObject($vehicleId);
        // TODO: clear rental history
        $this -> delete($result);
    }

    public function getVehicleData($vehicleId): array
    {
        $record = $this -> getVehicleObject($vehicleId);

        return [
            'id' => $record -> getId(),
            'brandId' => $record -> getBrand() -> getId(),
            'model' => $record -> getType(),
            'fuelTypeId' => $record -> getFuel() -> getId(),
            'fuelTypeName' => $record -> getFuel() -> getFuel(),
            'description' => $record -> getDescription(),
            'vin' => $record -> getVin(),
            'plate' => $record -> getPlate(),
            'maintenance' => $record -> isMaintenance(),
        ];
    }

    public function updateVehicleData($vehicleId, $brandId, $type, $fuelTypeId, $description, $vin, $plate, $maintenance): void
    {
        $record = $this -> getVehicleObject($vehicleId);
        $record -> setBrand($this -> getBrandObject($brandId));
        $record -> setType(trim($type));
        $record -> setFuel($this -> getFuelTypeObject($fuelTypeId));
        $record -> setDescription(trim($description));
        $record -> setVin(trim($vin));
        $record -> setPlate(trim($plate));
        $record -> setMaintenance($maintenance);

        $this -> save($record);
    }

    public function validateFields(Request $request): array
    {
        $fields = [
            'brandId' => 'Marka',
            'model' => 'Model',
            'fuelTypeId' => 'Paliwo',
            'vin' => 'VIN',
            'plate' => 'Numer rejestracyjny'
        ];

        $missingFields = [];

        foreach ($fields as $key => $label) {
            if (empty(trim($request -> $key))) {
                $missingFields[] = $label;
            }
        }

        return $missingFields;
    }
}