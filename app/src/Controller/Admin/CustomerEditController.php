<?php

namespace App\Controller\Admin;

use App\Service\CustomerService;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerEditController extends AbstractController
{
    public function __construct(
        private readonly CustomerService $customerService
    ){}

    /**
     * @throws Exception
     */
    #[Route('/admin/customer-edit', name: 'admin_customer-edit')]
    public function index(Request $request): Response
    {
        // validate customer id
        $customerId = $request -> get('customerId');
        if (false === $this -> customerService -> doesCustomerIdExist(trim((int) $customerId))) {
            $this -> addFlash('error', 'Brak takiego użytkownika pojazdu');
            return $this -> redirectToRoute('admin_customer-list');
        }

        if ($request -> isMethod('POST')) {

            $missingFields = $this -> customerService -> validateFields($request);

            if (!empty($missingFields)) {
                $this -> addFlash('error', 'Brakujące pola: ' . implode(', ', $missingFields) . '!');
                return $this -> redirect('/admin/customer-edit?customerId='.$customerId);
            }
            try {
                // city exists?
                if (false === $this -> customerService -> doesCityExist((int) trim($request -> get('cityId')))) {
                    throw new InvalidArgumentException('Brak takiej miejscowości');
                }

                $this -> customerService -> updateCustomer(
                    $customerId,
                    $request -> get('fullName'),
                    $request -> get('address'),
                    $request -> get('cityId')
                );

                $this -> addFlash('success', 'Zmiany zostały zapisane');

            } catch (InvalidArgumentException $e) {
                $this -> addFlash('error', $e -> getMessage());

                return $this -> redirectToRoute('admin_customer-edit');
            }
        }
        return $this->render('admin/customer-edit.html.twig', [
            'customerData' => $this -> customerService -> getCustomerData($customerId),
            'cityArray' => $this -> customerService -> getCityArray(),
            'customerId' => $customerId,
        ]);
    }
}