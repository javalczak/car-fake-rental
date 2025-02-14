<?php

namespace App\Controller\Admin;

use App\Service\CustomerService;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerAddController extends AbstractController
{
    public function __construct(
        private readonly CustomerService $customerService
    ){}

    /**
     * @throws Exception
     */
    #[Route('/admin/customer-add', name: 'admin_customer-add')]
    public function index(Request $request): Response
    {
        // simple validation
        if ($request -> isMethod('POST')) {

            $missingFields = $this -> customerService -> validateFields($request);

            if (!empty($missingFields)) {
                $this -> addFlash('error', 'Brakujące pola: ' . implode(', ', $missingFields) . '!');
                return $this -> redirectToRoute('admin_customer-add');
            }
            try {
                // city exists?
                if (false === $this -> customerService -> doesCityExist((int) trim($request -> get('cityId')))) {
                    throw new InvalidArgumentException('Brak takiej miejscowości');
                }

                $this -> customerService -> addCustomer(
                    $request -> get('fullName'),
                    $request -> get('address'),
                    $request -> get('cityId')
                );
                $this -> addFlash('success', 'Użytkownik pojazdu został dodany pomyślnie');

            } catch (InvalidArgumentException $e) {
                $this -> addFlash('error', $e -> getMessage());

                return $this -> redirectToRoute('admin_customer-add');
            }
        }

        return $this->render('admin/customer-add.html.twig', [
            'cityArray' => $this -> customerService -> getCityArray(),
        ]);
    }
}