<?php

namespace App\Controller\Api;

use App\Service\Api\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Vehicle extends AbstractController
{
    public function __construct(
        private readonly ApiService $apiService,
    ){}

    #[Route('api/vehicle', name: 'api-vehicle-list', methods: ['GET'])]
    public function getVehicle(): JsonResponse
    {
        return new JsonResponse(['items' => $this -> apiService -> getVehicleArray()], Response::HTTP_OK);
    }
}