<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Utils\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminCustomerController extends AbstractController
{
    /**
     * @Route("/admin/customers/{page}", name="admin_customers_index", requirements={"page": "\d+"})
     */
    public function index(CustomerRepository $repo, $page = 1, Pagination $pagination)
    {
        $pagination->setEntityClass(Customer::class)
                   ->setPage($page);

        return $this->render('admin/customer/index.html.twig', [
            'customers' => $pagination->getData(),
            'pages' => $pagination->getPages(),
            'page' => $page
        ]);
    }
}
