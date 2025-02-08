<?php

namespace App\Controller\Admin;

use App\Service\CustomerService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerListController extends AbstractController
{
    public function __construct(
        private readonly CustomerService $customerService
    ){}

    /**
     * @throws Exception
     */
    #[Route('/admin/customer-list', name: 'admin_customer-list')]
    public function index(Request $request): Response
    {
        $deleteId = $request -> get('deleteId');

        if ($deleteId) {
            try {
                if ($this -> customerService -> doesCustomerCanBeDeleted($deleteId)) {
                    $this -> customerService -> deleteCustomer($deleteId);
                    $this -> addFlash('success', 'Użytkownik pojazdu został usunięty!');
                }
            } catch (Exception $e) {
                $this -> addFlash('error', 'Błąd: ' . $e -> getMessage());
            }
        }

        return $this->render('admin/customer-list.html.twig', [
            'cityArray' => $this -> customerService -> getCityArray(),
            'customerArray' => $this -> customerService -> getCustomerArray(),
        ]);
    }
}