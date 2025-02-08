<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RentVehicleController extends AbstractController
{
    #[Route('/user/vehicle-ge', name: 'user_vehicle-rent')]
    public function index(): Response
    {
        return $this -> render('user/rent-vehicle.html.twig');
    }
}