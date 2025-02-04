<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VehicleListController extends AbstractController
{
    #[Route('/user/vehicle-list', name: 'user_vehicle-list')]
    public function index(): Response
    {
        return $this->render('user/vehicle-list.html.twig');
    }
}
