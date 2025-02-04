<?php

namespace App\Controller\Api;

use App\Service\Api\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class Vehicle extends AbstractController
{
    public function __construct(
        private readonly ApiService $apiService,
    ) {}

    #[Route('/api/vehicle', name: 'api-vehicle-list', methods: ['GET'])]
    #[OA\Get(
        description: "Zwraca listę pojazdów w formacie JSON",
        summary: "Pobiera listę pojazdów",
        tags: ["Vehicle"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista pojazdów",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "items", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer"),
                                new OA\Property(property: "brand", type: "string"),
                                new OA\Property(property: "type", type: "string"),
                                new OA\Property(property: "fuelType", type: "string"),
                                new OA\Property(property: "vin", type: "string"),
                                new OA\Property(property: "plate", type: "string"),
                                new OA\Property(property: "inMaintenance", type: "int"),
                                new OA\Property(property: "description", type: "string"),
                                new OA\Property(property: "inUse", type: "integer"),
                            ]
                        ))
                    ]
                )
            )
        ]
    )]
    public function getVehicle(): JsonResponse
    {
        return new JsonResponse(['items' => $this->apiService->getVehicleArray()], Response::HTTP_OK);
    }
}
