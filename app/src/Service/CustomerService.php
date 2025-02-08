<?php

namespace App\Service;

use App\Entity\Customer;
use DateTime;
use Exception;
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
            throw new Exception('Tabela miejscowości jest pusta');
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
            'address' => 'Adres'
        ];

        $missingFields = [];

        foreach ($fields as $key => $label) {
            if (empty(trim($request -> get($key)))) {
                $missingFields[] = $label;
            }
        }

        return $missingFields;
    }

    public function generateUniqueCode(): string
    {
        return substr(str_shuffle(str_repeat(
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 6
        )), 0, 6);
    }

    public function addCustomer($fullName, $address, $cityId): ? string
    {
        $newCustomer = new Customer();
        $newCustomer -> setFullName(trim($fullName));
        $newCustomer -> setAddress(trim($address));
        $newCustomer -> setIdNumber($this -> generateUniqueCode());
        $newCustomer -> setCity($this -> getCityObject($cityId));
        $newCustomer -> setAddedAt(new DateTime('now'));

        $this -> save($newCustomer);

        return $newCustomer -> getIdNumber();
    }

    public function updateCustomer($customerId, $fullName, $address, $cityId): ? string
    {
        $customer = $this -> getCustomerObject($customerId);
        $customer -> setFullName(trim($fullName));
        $customer -> setAddress(trim($address));
        $customer -> setCity($this -> getCityObject($cityId));

        $this -> save($customer);

        return $customer -> getIdNumber();
    }

    public function doesCityExist($cityId): bool
    {
        if (null === $this -> getCityObject($cityId)) {
            return false;
        } else {
            return true;
        }
    }

    public function getCustomerArray(): array
    {
        $result = $this -> customerRepo ->  createQueryBuilder('table')
            -> select('table')
            -> orderBy('table.addedAt','DESC')
            -> getQuery()
            -> getResult();

        $customerArray = [];
        /** @var Customer $item */
        foreach ($result as $item) {
            $customerArray[] = [
                'id' => $item -> getId(),
                'fullName' => $item -> getFullName(),
                'address' => $item -> getAddress(),
                'idNumber' => $item -> getIdNumber(),
                'addedAt' => $item -> getAddedAt(),
                'city' => $item -> getCity() -> getName()
            ];
        }

        return $customerArray;
    }

    public function doesCustomerIdExist($customerId)
    {
        if (null === $this -> getCustomerObject($customerId)) {
            return false;
        } else {
            return true;
        }
    }

    public function getCustomerData($customerId): array
    {
        $record = $this -> getCustomerObject($customerId);

        return [
            'id' => $record -> getId(),
            'city' => $record -> getCity() -> getName(),
            'fullName' => $record -> getFullName(),
            'address' => $record -> getAddress(),
            'idNumber' => $record -> getIdNumber(),
            'addedAt' => $record -> getAddedAt(),
        ];
    }

    /**
     * @throws Exception
     */
    public function doesCustomerCanBeDeleted($customerId): true
    {
        // TODO: sprawdzić, czy ma jakieś auto

        // Sprawdzamy, czy taki customer istnieje
        if (null === $this -> getCustomerObject($customerId)) {
            throw new Exception('Nie mamy takiego użytkownika');
        }
        // Sprawdzamy, czy jest w użyciu
        // TODO: later

        return true;
    }

    public function deleteCustomer($customerId): void
    {
        $this -> delete($this -> getCustomerObject($customerId));
    }
}