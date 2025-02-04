<?php

namespace App\Controller\Admin;

use App\Service\VehicleService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VehicleListController extends AbstractController
{
    public function __construct(
        private readonly VehicleService $vehicleService
    ) {}

    #[Route('/admin/vehicle-list', name: 'admin_vehicle-list')]
    #[Route('/admin/', name: 'admin')]
    public function index(Request $request): Response
    {
        $deleteId = $request -> get('deleteId');

        if ($deleteId) {
            try {
                if ($this -> vehicleService -> doesVehicleCanBeDeleted($deleteId)) {
                    $this -> vehicleService -> deleteVehicle($deleteId);
                    $this -> addFlash('success', 'Pojazd został usunięty!');
                }
            } catch (Exception $e) {
                $this -> addFlash('error', 'Błąd: ' . $e -> getMessage());
            }

            return $this -> redirectToRoute('admin_vehicle-list');
        }

        $vehicleArray = $this->vehicleService -> getVehicleArray();

        return $this -> render('admin/vehicle-list.html.twig', [
            'vehicleArray' => $vehicleArray,
        ]);
    }
}