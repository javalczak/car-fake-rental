<?php

namespace App\Controller\Admin;

use App\Service\VehicleService;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VehicleAddController extends AbstractController
{
    public function __construct(
        private readonly VehicleService $vehicleService
    ){}

    /**
     * @throws Exception
     */
    #[Route('/admin/vehicle-add', name: 'admin_vehicle-add')]
    public function index(Request $request): Response
    {
        // simple validation
        if ($request -> isMethod('POST')) {

            $fields = [
                'brandId' => 'Marka',
                'model' => 'Model',
                'fuelTypeId' => 'Paliwo',
                'vin' => 'VIN',
                'plate' => 'Numer rejestracyjny'
            ];

            $missingFields = [];

            foreach ($fields as $key => $label) {
                if (empty(trim($request -> get($key)))) {
                    $missingFields[] = $label;
                }
            }

            if (!empty($missingFields)) {
                $this -> addFlash('error', 'Brakujące pola: ' . implode(', ', $missingFields) . '!');
                return $this -> redirectToRoute('admin_vehicle-add');
            }

            try {
                $maintenance = $request -> get('maintenance') !== null;
                $this -> vehicleService -> addNewVehicle(
                    $request -> get('brandId'),
                    $request -> get('model'),
                    $request -> get('fuelTypeId'),
                    $request -> get('description'),
                    $request -> get('vin'),
                    $request -> get('plate'),
                    $maintenance
                );
                $this -> addFlash('success', 'Pojazd został dodany pomyślnie!');
            } catch (InvalidArgumentException $e) {
                $this -> addFlash('error', $e -> getMessage());

                return $this -> redirectToRoute('admin_vehicle-add');
            }
        }

        return $this->render('admin/vehicle-add.html.twig', [
            'fuelTypeArray' => $this -> vehicleService -> getFuelTypeArray(),
            'brandArray' => $this -> vehicleService -> getBrandArray(),
        ]);
    }
}