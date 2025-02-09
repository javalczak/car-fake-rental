<?php

namespace App\Controller\User;

use App\Service\VehicleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RentVehicleController extends AbstractController
{
    public function __construct(
        private readonly VehicleService $vehicleService
    ){}

    #[Route('/user/rent', name: 'user_vehicle-rent')]
    public function index(Request $request): Response
    {
        // need vehicle id
        $vehicleId = $request -> get('vehicleId');

        // validation
        if (null === $this -> vehicleService -> getVehicleObject((int) trim($vehicleId))) {
            $this -> addFlash('error', 'Brak takiego pojazdu');
            return $this -> redirectToRoute('user_vehicle-list');
        }

        // vehicle used or not
        if (true === $this -> vehicleService -> isVehicleUsedNow($vehicleId)) {
            $story = 'release';
        } else {
            $story = 'rent';
        }

        $vehicleName = $this -> vehicleService -> getVehicleObject($vehicleId) -> getBrand() -> getName() .' '.$this -> vehicleService -> getVehicleObject($vehicleId) -> getType();

        return $this -> render('user/rent.html.twig', [
            'vehicleId' => $vehicleId,
            'story' => $story,
            'vehicleName' => $vehicleName,
        ]);
    }
}