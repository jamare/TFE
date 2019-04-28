<?php

namespace App\Controller;


use App\Entity\Customer;
use App\Entity\Provider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_show")
     */
    public function index(Customer $customer)
    {
       return $this->render('user/index.html.twig', [
                'customer' => $customer
       ]);

    }
}
