<?php

namespace App\Controller\Admin;

use App\Service\VehicleService;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VehicleEditController extends AbstractController
{
    public function __construct(
        private readonly VehicleService $vehicleService
    ){}

    /**
     * @throws Exception
     */
    #[Route('/admin/vehicle-edit', name: 'admin_vehicle-edit')]
    public function index(Request $request): Response
    {
        $vehicleId = $request -> get('vehicleId');

        if ($request -> isMethod('POST')) {
            // TODO: move to service, just duplicate

//            // simple validation
//            if (!$this -> vehicleService -> getVehicleObject($vehicleId)) {
//                $this -> addFlash('error', 'Brak takiego pojazdu!');
//                return $this -> redirect('/admin/vehicle-list');
//            }
//
//            $missingFields = $this -> vehicleService -> validateFields($request);
//
//            if (!empty($missingFields)) {
//                $this -> addFlash('error', 'Brakujące pola: ' . implode(', ', $missingFields) . '!');
//                return $this -> redirectToRoute('admin_vehicle-edit', [
//                    'vehicleId' => $vehicleId,
//                ]);
//            }
//        }

//        try {
            $maintenance = $request -> get('maintenance') !== null;
            $this->vehicleService -> updateVehicleData(
                $vehicleId,
                $request -> get('brandId'),
                $request -> get('model'),
                $request -> get('fuelTypeId'),
                $request -> get('description'),
                $request -> get('vin'),
                $request -> get('plate'),
                $maintenance
            );
            $this -> addFlash('success', 'Zmiany zostały zapisane!');
        }

//
//
//        } catch (InvalidArgumentException $e) {
//            $this -> addFlash('error', $e -> getMessage());
//
//            return $this -> redirectToRoute('admin_vehicle-edit', [
//                'vehicleId' => $vehicleId,
//            ]);
//        }

        return $this->render('admin/vehicle-edit.html.twig', [
            'vehicleData' => $this -> vehicleService -> getVehicleData($vehicleId),
            'fuelTypeArray' => $this -> vehicleService -> getFuelTypeArray(),
            'brandArray' => $this -> vehicleService -> getBrandArray(),
            'vehicleId' => $vehicleId,
        ]);
    }
}