<?php

namespace App\Controller\User;

use App\Service\CustomerService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GetCodeController extends AbstractController
{
    public function __construct(
        private readonly CustomerService $customerService
    ){}

    /**
     * @throws Exception
     */
    #[Route('/user/get-code', name: 'user_get-code')]
    public function index(): Response
    {
        return $this->render('user/get-code.html.twig', [
            'cityArray' => $this -> customerService -> getCityArray(),
        ]);
    }
}
