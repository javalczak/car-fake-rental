<?php

namespace App\Controller\Admin;

use App\Service\VehicleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BrandAddController extends AbstractController
{
    public function __construct(
        private readonly VehicleService $vehicleService
    ){}

    #[Route('/admin/brand-add', name: 'admin_brand-add')]
    public function index(Request $request): Response
    {
        // simple validation
        if ($request -> isMethod('POST')) {
            $brand = trim($request -> get('brand'));

            if (empty($brand)) {
                $this -> addFlash('error', 'Marka nie może być pusta!');

                return $this -> redirectToRoute('admin_brand-add');
            }

            if (true === $this -> vehicleService -> doesBrandExists($brand)) {
                $this -> addFlash('error', 'Marka \''.$brand.'\' jest już w bazie!');

                return $this -> redirectToRoute('admin_brand-add');
            }

            // add new brand
            if (true === $this -> vehicleService -> addNewBrand($brand)) {
                $this -> addFlash('ok', 'Marka \''.$brand.'\' została dodana');

                return $this -> redirectToRoute('admin_brand-add');
            }
        }

        return $this -> render('admin/brand-add.html.twig');
    }
}