<?php

namespace App\Service;

use App\Entity\Customer;
use DateTime;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class CustomerService extends AbstractService
{
    /**
     * @throws Exception
     */
    public function getCityArray(): array
    {
        $result = $this -> cityRepo -> createQueryBuilder('table')
            -> select('table')
            -> orderBy('table.name', 'ASC')
            -> getQuery()
            -> getResult();

        if (empty($result)) {
            throw new Exception('Wynik jest pusty!');
        }

        $cityArray = [];
        foreach ($result as $item) {
            $cityArray[] = [
                'id' => $item -> getId(),
                'name' => $item -> getName()
            ];
        }

        return $cityArray;
    }

    public function validateFields(Request $request): array
    {
        $fields = [
            'fullName' => 'Imię i nazwisko',
            'address' => 'Adres',
            'id' => 'Numer dowodu osobistego'
        ];

        $missingFields = [];

        foreach ($fields as $key => $label) {
            if (empty(trim($request -> get($key)))) {
                $missingFields[] = $label;
            }
        }

        return $missingFields;
    }

    public function addCustomer($fullName, $address, $idNumber, $cityId): void
    {
        // validation
        if (empty($fullName) || empty($address) || empty($idNumber) || empty($cityId)) {
            throw new InvalidArgumentException('Wszystkie pola są wymagane!');
        }

        if (!$this -> getCityObject($cityId)) {
            throw new InvalidArgumentException('Brak wskazanej miejscowości');
        }

        $newCustomer = new Customer();
        $newCustomer -> setFullName(trim($fullName));
        $newCustomer -> setAddress(trim($address));
        $newCustomer -> setIdNumber(trim($idNumber));
        $newCustomer -> setCity($this -> getCityObject($cityId));
        $newCustomer -> setAddedAt(new DateTime('now'));

        $this -> save($newCustomer);
    }


//
//    public function doesVehicleCanBeDeleted($vehicleId): bool
//    {
//        // sprawdzamy, czy taki vehicle istnieje
//        if (false === $this -> vehicleRepo -> find($vehicleId)) {
//            throw new Exception('Nie mamy takiej fury na stanie');
//        }
//
//        // sprawdzamy, czy jest w użyciu
//        // TODO: later
//
//        return true;
//    }
//


//
//    public function getVehicleArray(): array
//    {
//        $result = $this -> vehicleRepo -> createQueryBuilder('table')
//            -> select('table')
//            -> orderBy('table.id', 'DESC')
//            -> getQuery()
//            -> getResult();
//
//        $vehicleArray = [];
//        /** @var Vehicle $item */
//        foreach ($result as $item) {
//            $vehicleArray[] = [
//                'id' => $item -> getId(),
//                'brand' => $item -> getBrand() -> getName(),
//                'type' => $item -> getType(),
//                'maintenance' => $item -> isMaintenance(),
//                'plate' => $item -> getPlate(),
//                'description' => $item -> getDescription(),
//                'fuel' => $item -> getFuel() -> getFuel()
//            ];
//        }
//
//        return $vehicleArray;
//    }
//
//    /**
//     * @throws Exception
//     */
//    public function doesVehicleCanBeDeleted($vehicleId): bool
//    {
//        // sprawdzamy, czy taki vehicle istnieje
//        if (false === $this -> vehicleRepo -> find($vehicleId)) {
//            throw new Exception('Nie mamy takiej fury na stanie');
//        }
//
//        // sprawdzamy, czy jest w użyciu
//        // TODO: later
//
//        return true;
//    }
//
//    public function deleteVehicle($vehicleId): void
//    {
//        $result = $this -> getVehicleObject($vehicleId);
//        // TODO: clear rental history
//        $this -> delete($result);
//    }
//
//    public function getVehicleData($vehicleId): array
//    {
//        $record = $this -> getVehicleObject($vehicleId);
//
//        return [
//            'id' => $record -> getId(),
//            'brandId' => $record -> getBrand() -> getId(),
//            'model' => $record -> getType(),
//            'fuelTypeId' => $record -> getFuel() -> getId(),
//            'fuelTypeName' => $record -> getFuel() -> getFuel(),
//            'description' => $record -> getDescription(),
//            'vin' => $record -> getVin(),
//            'plate' => $record -> getPlate(),
//            'maintenance' => $record -> isMaintenance(),
//        ];
//    }
//
//    public function updateVehicleData($vehicleId, $brandId, $type, $fuelTypeId, $description, $vin, $plate, $maintenance): void
//    {
//        $record = $this -> getVehicleObject($vehicleId);
//        $record -> setBrand($this -> getBrandObject($brandId));
//        $record -> setType(trim($type));
//        $record -> setFuel($this -> getFuelTypeObject($fuelTypeId));
//        $record -> setDescription(trim($description));
//        $record -> setVin(trim($vin));
//        $record -> setPlate(trim($plate));
//        $record -> setMaintenance($maintenance);
//
//        $this -> save($record);
//    }
//
//    public function validateFields(Request $request): array
//    {
//        $fields = [
//            'brandId' => 'Marka',
//            'model' => 'Model',
//            'fuelTypeId' => 'Paliwo',
//            'vin' => 'VIN',
//            'plate' => 'Numer rejestracyjny'
//        ];
//
//        $missingFields = [];
//
//        foreach ($fields as $key => $label) {
//            if (empty(trim($request -> $key))) {
//                $missingFields[] = $label;
//            }
//        }
//
//        return $missingFields;
//    }
}