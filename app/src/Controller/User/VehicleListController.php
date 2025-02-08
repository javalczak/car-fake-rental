<?php

namespace App\Controller\User;

use App\Service\VehicleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VehicleListController extends AbstractController
{
    public function __construct(
        private readonly VehicleService $vehicleService,
    ){}

    #[Route('/user/vehicle-list', name: 'user_vehicle-list')]
    public function index(Request $request): Response
    {
        if ($request -> getMethod() === 'POST') {

            $deleteId = $request -> get('deleteId');

            // if exists and not in use:
            // TODO: if not in use






            foreach ($languageArray as $language) {

                if (isset($message[$language])) {
                    $notificationService -> addNotification($language, $message[$language], $workName, $ticket);
                }
            }

            return $this -> redirect('/admin/notifications-list');
        }


        return $this->render('user/vehicle-list.html.twig');
    }
}
