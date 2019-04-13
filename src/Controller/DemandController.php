<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Repository\DemandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandController extends AbstractController
{
    /**
     * @Route("/demands", name="demands_index")
     */
    public function index(DemandRepository $repo)
    {
       $demands = $repo->findAll();

        return $this->render('demand/index.html.twig', [
            'demands' => $demands
        ]);
    }

    /**
     * Permet de créer une annonce
     *
     * @Route("/demands/new", name="demands_create")
     *
     * @return Response
     */
    public function create(){
        return $this->render('demand/new.html.twig');
    }


    /**
     * Affichage d'une seule annonce
     *
     * @Route("/demands/{id}", name="demands_show")
     *
     * @return Response
     */
    public function show(demand $demand){
        return $this->render('demand/show.html.twig',[
            'demand' => $demand
        ]);
    }


}
