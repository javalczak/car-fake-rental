<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/user', name: 'app_home_user')]
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('user/home.html.twig');
    }
}
