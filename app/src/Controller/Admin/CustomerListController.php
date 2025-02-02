<?php

namespace App\Controller\Admin;

use App\Service\CustomerService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerListController extends AbstractController
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




//
//            try {
//                $maintenance = $request -> get('maintenance') !== null;
//                $this -> vehicleService -> addNewVehicle(
//                    $request -> get('brandId'),
//                    $request -> get('model'),
//                    $request -> get('fuelTypeId'),
//                    $request -> get('description'),
//                    $request -> get('vin'),
//                    $request -> get('plate'),
//                    $maintenance
//                );
//                $this -> addFlash('success', 'Pojazd został dodany pomyślnie!');
//            } catch (InvalidArgumentException $e) {
//                $this -> addFlash('error', $e -> getMessage());
//
//                return $this -> redirectToRoute('admin_vehicle-add');
//            }
        }

        return $this->render('admin/customer-add.html.twig', [
            'cityArray' => $this -> customerService -> getCityArray(),
        ]);
    }
}