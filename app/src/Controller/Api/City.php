<?php

namespace App\Controller\Api;

use App\Service\Api\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class City extends AbstractController
{
    public function __construct(
        private readonly ApiService $apiService,
    ) {}

    #[Route('/api/city', name: 'api-city-list', methods: ['GET'])]
    #[OA\Get(
        description: "Zwraca listę miejscowości w formacie json",
        summary: "Pobiera listę miejscowości",
        tags: ["city"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista miejscowości",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "items", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "name", type: "string")]
                        ))
                    ]
                )
            )
        ]
    )]
    public function getCIty(): JsonResponse
    {
        return new JsonResponse(['items' => $this -> apiService -> getCityArray()], Response::HTTP_OK);
    }
}
