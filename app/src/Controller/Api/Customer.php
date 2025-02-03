<?php

namespace App\Controller\Api;

use App\Service\Api\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Customer extends AbstractController
{
    public function __construct(
        private readonly ApiService $apiService,
    ) {}

    #[Route('/api/customer', name: 'api-customer-add', methods: ['POST'])]
    public function setCustomer(Request $request): JsonResponse
    {
        $params = json_decode($request -> getContent(), true);

        // validation
        if (true === $this -> apiService -> addCustomerValidator($params)) {
            $this -> apiService -> addCustomer($params['fullName'], $params['address'], $params['idNumber'], $params['cityId']);

            return new JsonResponse([], Response::HTTP_OK);

        } else {

            return $this -> json([
                'success' => false,
                'error' => [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Brakuje parametrów',
                    'details' => [
                        'fullName' => $params['fullName'] ?? 'Missing',
                        'address' => $params['address'] ?? 'Missing',
                        'idNumber' => $params['idNumber'] ?? 'Missing',
                        'cityId' => $params['cityId'] ?? 'Missing',
                    ],
                ]
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
