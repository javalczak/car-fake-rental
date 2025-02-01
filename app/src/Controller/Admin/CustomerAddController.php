<?php

namespace App\Controller\Admin;

use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerAddController extends AbstractController
{
    public function __construct(

    ){}

    /**
     * @throws Exception
     */
    #[Route('/admin/customer-add', name: 'admin_customer-add')]
    public function index(Request $request): Response
    {
        // simple validation
//        if ($request -> isMethod('POST')) {
//
//            $missingFields = $this -> vehicleService -> validateFields($request);
//
//            if (!empty($missingFields)) {
//                $this -> addFlash('error', 'Brakujące pola: ' . implode(', ', $missingFields) . '!');
//                return $this -> redirectToRoute('admin_vehicle-add');
//            }
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
//        }

        return $this->render('admin/vehicle-add.html.twig', [
            'fuelTypeArray' => $this -> vehicleService -> getFuelTypeArray(),
            'brandArray' => $this -> vehicleService -> getBrandArray(),
        ]);
    }
}