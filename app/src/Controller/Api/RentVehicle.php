<?php

namespace App\Controller\Api;

use App\Dto\RentVehicleDto;
use App\Service\Api\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class RentVehicle extends AbstractController
{
    public function __construct(
        private readonly ApiService $apiService,
    ){}

    #[Route('/api/rent', name: 'api_vehicle-rent', methods: ['POST'])]
    public function setRentVehicle(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $params = json_decode($request -> getContent(), true);

        $dto = new RentVehicleDto();
        $dto -> vehicleId = $params['vehicleId'] ?? null;
        $dto -> rentalCode = $params['rentalCode'] ?? null;

        // validate
        if ($response = $this -> validateDto($dto, $validator)) {
            return $response;
        }

        // in use?
        if (true ===  $this -> apiService -> doesVehicleIsInUse($dto -> vehicleId)) {
            return $this -> json(['success' => false, 'message' => '', 'errorMessage' => "Auto niedostępne"], Response::HTTP_OK);
        }

        $this -> apiService -> rentVehicle($dto -> vehicleId, $dto -> rentalCode);
        return $this -> json(['success' => true, 'message' => 'Auto wynajęte'], Response::HTTP_OK);
    }

    #[Route('/api/rent', name: 'api_vehicle-rent-delete', methods: ['DELETE'])]
    public function deleteRentVehicle(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $params = json_decode($request -> getContent(), true);

        $dto = new RentVehicleDto();
        $dto -> vehicleId = $params['vehicleId'] ?? null;
        $dto -> rentalCode = $params['rentalCode'] ?? null;

        // validate
        if ($response = $this -> validateDto($dto, $validator)) {
            return $response;
        }

        // vehicle must be in use by the same user
        if (false === $this -> apiService -> canWeReleaseVehicle($dto -> vehicleId, $dto -> rentalCode)) {
            return $this -> json(['success' => false, 'message' => '', 'errorMessage' => "Nie możesz zwrócić tego auta"], Response::HTTP_OK);
        }

        // release vehicle
        $this -> apiService -> releaseVehicle($dto -> vehicleId);
        return $this -> json(['success' => false, 'message' => 'Auto zwrócone'], Response::HTTP_OK);
    }

    private function validateDto($dto, ValidatorInterface $validator): ?JsonResponse
    {
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['success' => false, 'errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        return null;
    }
}
