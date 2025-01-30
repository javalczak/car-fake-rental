<?php

namespace App\Service;

use App\Entity\Brand;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

    public function addNewVehicle()
    {
//        $newBrand = new Brand();
//        $newBrand -> setName(]

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
}