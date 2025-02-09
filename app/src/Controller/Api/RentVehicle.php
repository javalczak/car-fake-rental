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
    ){}

    #[Route('/api/rent', name: 'api_vehicle-rent', methods: ['POST'])]
    #[OA\Post(
        description: "Wynajmuje pojazd",
        summary: "Wynajmuje pojazd na podstawie podanego ID pojazdu i kodu wynajmu",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["vehicleId", "rentalCode"],
                properties: [
                    new OA\Property(property: "vehicleId", description: "ID pojazdu do wynajęcia", type: "integer"),
                    new OA\Property(property: "rentalCode", description: "Kod wynajmu pojazdu", type: "string")
                ]
            )
        ),
        tags: ["Vehicle"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Pojazd wynajęty pomyślnie",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Pojazd wynajęty pomyślnie")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Błędy walidacji",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "errors", type: "array", items: new OA\Items(type: "string"))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Pojazd niedostępny",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "errorMessage", type: "string", example: "Pojazd niedostępny")
                    ]
                )
            )
        ]
    )]
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

    #[Route('/api/release', name: 'api_vehicle-rent-delete', methods: ['POST'])]
    #[OA\Post(
        description: "Zwraca wynajęty pojazd",
        summary: "Zwraca pojazd na podstawie podanego ID pojazdu i kodu wynajmu",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["vehicleId", "rentalCode"],
                properties: [
                    new OA\Property(property: "vehicleId", description: "ID pojazdu do zwrotu", type: "integer"),
                    new OA\Property(property: "rentalCode", description: "Kod wynajmu pojazdu", type: "string")
                ]
            )
        ),
        tags: ["Vehicle"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Pojazd zwrócony pomyślnie",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Pojazd zwrócony pomyślnie")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Błędy walidacji",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "errors", type: "array", items: new OA\Items(type: "string"))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Pojazd niedostępny",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "errorMessage", type: "string", example: "Pojazd niedostępny")
                    ]
                )
            )
        ]
    )]
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
        $customerId = $this -> apiService -> getCustomerIdViaRentalCode($dto -> rentalCode);
        $this -> apiService -> releaseVehicle($dto -> vehicleId, $customerId);
        return $this -> json(['success' => true, 'message' => 'Auto zwrócone'], Response::HTTP_OK);
    }

    private function validateDto($dto, ValidatorInterface $validator): ?JsonResponse
    {
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['success' => false, 'errorMessage' => $errorMessages], Response::HTTP_OK);
        }

        return null;
    }
}
