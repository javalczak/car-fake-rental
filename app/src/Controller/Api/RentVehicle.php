<?php

namespace App\Controller\Api;

use App\Dto\RentVehicleDto;
use App\Service\Api\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RentVehicle extends AbstractController
{
    public function __construct(
        private readonly ApiService $apiService,
    )
    {
    }

    #[Route('/api/vehicle-rent/{vehicleId}', name: 'api_vehicle-rent', methods: ['POST'])]
    public function setRentVehicle($vehicleId, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $params = json_decode($request->getContent(), true);

        $dto = new RentVehicleDto();
        $dto -> vehicleId = $params['vehicleId'] ?? null;
        $dto -> login = $params['login'] ?? null;

        // validate
        $errors = $validator -> validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['success' => false, 'errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        // customer exists



        $rentId = 1;

//        $customerId = $this -> apiService -> addCustomer(
//            $dto -> fullName,
//            $dto -> address,
//            $dto -> idNumber,
//            $dto -> cityId
//        );

        return $this->json(['success' => true, 'rent id:' => $rentId], Response::HTTP_OK);
    }

}
