<?php

namespace App\Controller\Admin;

use App\Service\VehicleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VehicleAddController extends AbstractController
{
    public function __construct(
        private readonly VehicleService $vehicleService
    ){}

    #[Route('/admin/vehicle-add', name: 'admin_vehicle-add')]
    public function index(): Response
    {
        return $this->render('admin/vehicle-add.html.twig', [
            'fuelTypeArray' => $this -> vehicleService -> getFuelTypeArray(),
        ]);
    }


}