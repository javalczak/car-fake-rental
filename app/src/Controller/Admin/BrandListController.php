<?php

namespace App\Controller\Admin;

use App\Service\BrandService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BrandListController extends AbstractController
{
    public function __construct(
        private readonly BrandService $brandService
    ){}

    /**
     * @throws Exception
     */
    #[Route('/admin/brand-list', name: 'admin_brand-list')]
    public function index(Request $request): Response
    {
        $deleteId = $request -> get('deleteId');

        if ($deleteId) {
            try {
                if ($this -> brandService -> doesBrandCanBeDeleted($deleteId)) {
                    $this -> brandService -> deleteBrand($deleteId);
                    $this -> addFlash('success', 'Marka pojazdu została usunięta');
                }
            } catch (Exception $e) {
                $this -> addFlash('error', 'Błąd: ' . $e -> getMessage());
            }
        }

        return $this->render('admin/brand-list.html.twig', [
            'brandArray' => $this -> brandService -> getBrandArray(),
        ]);
    }
}