<?php

namespace App\Service;

use App\Entity\Brand;

class BrandService extends AbstractService
{
    public function getBrandArray(): array
    {
        $result = $this -> brandRepo -> createQueryBuilder('table')
            -> select('table')
            -> orderBy('table.id', 'DESC')
            -> getQuery()
            -> getResult();

        $brandArray = [];
        /** @var Brand $item */
        foreach ($result as $item) {
            $brandArray[] = [
                'id' => $item -> getId(),
                'name' => $item -> getName()
            ];
        }

        return $brandArray;
    }

    public function doesBrandCanBeDeleted($brandId)
    {
        // TODO: sprawz czy mozesz
        return true;
    }

    public function deleteBrand($brandId): void
    {
        $this -> delete($this -> getBrandObject($brandId));
    }
}