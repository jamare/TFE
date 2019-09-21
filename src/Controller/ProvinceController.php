<?php

namespace App\Controller;

use App\Entity\Province;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProvinceController extends AbstractController
{
    /**
     * Liste des provinces
     */
    public function list_provinces()
    {
        $repository = $this->getDoctrine()->getRepository(Province::class);
        $provinces = $repository->findAll();

        return $this->render('province/list.html.twig', [
            'provinces' => $provinces,
        ]);
    }
}
