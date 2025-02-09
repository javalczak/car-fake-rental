<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RentVehicleController extends AbstractController
{
    #[Route('/user/rent', name: 'user_vehicle-rent')]
    public function index(Request $request): Response
    {

        // potrzebuje id pojazdu
        $vehicleId = $request -> get('vehicleId');






        return $this -> render('user/rent.html.twig');
    }
}