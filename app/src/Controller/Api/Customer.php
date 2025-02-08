<?php

namespace App\Controller\Api;

use App\Dto\CreateCustomerDto;
use App\Service\Api\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class Customer extends AbstractController
{
    public function __construct(
        private readonly ApiService $apiService,
    ) {}

    #[Route('/api/customer', name: 'api-customer-add', methods: ['POST'])]
    #[OA\Post(
        description: "Dodaje nowego użytkownika pojazdu",
        summary: "Dodaje nowego użytkownika pojazdu do systemu",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["fullName", "address", "idNumber", "cityId"],
                properties: [
                    new OA\Property(property: "fullName", description: "Pełna nazwa klienta", type: "string"),
                    new OA\Property(property: "address", description: "Adres klienta", type: "string"),
                    new OA\Property(property: "cityId", description: "ID miasta, w którym mieszka klient", type: "integer"),
                ]
            )
        ),
        tags: ["Customer"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Użytkownik pojazdu dodany pomyślnie",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "customerId", description: "ID nowo dodanego klienta", type: "integer")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Złe żądanie (błędy walidacji)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "errors", type: "array", items: new OA\Items(type: "string"))
                    ]
                )
            )
        ]
    )]
    public function setCustomer(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $params = json_decode($request->getContent(), true);

        $dto = new CreateCustomerDto();
        $dto -> fullName = $params['fullName'];
        $dto -> address = $params['address'] ;
        $dto -> cityId = $params['cityId'];

        // validate
        $errors = $validator -> validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error -> getMessage();
            }
            return $this -> json(['success' => false, 'message' => '', 'errorMessage' => $errorMessages], Response::HTTP_OK);
        }

        $rentalCode = $this -> apiService -> generateRentalCode(8, 88888);

        $newCustomerId = $this -> apiService -> addCustomer($dto -> fullName, $dto -> address, $dto -> cityId, $rentalCode);

        return $this -> json(['success' => true, 'customerId' => $newCustomerId], Response::HTTP_OK);
    }
}
