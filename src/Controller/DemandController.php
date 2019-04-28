<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Form\DemandType;
use App\Repository\DemandRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request, ObjectManager $manager){

        $demand = new Demand();
        //$user = $this->getUser();

        $form = $this->createForm(DemandType::class, $demand);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $demand->setCustomer($user);
            $manager->persist($demand);
            $manager->flush();

            $this->addFlash(
                'success', 'La demande a bien été enregistrée !'
            );

            return $this->redirectToRoute('demands_show',[
                'id' => $demand->getId()
            ]);
        }

        return $this->render('demand/new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Edition d'une annonce
     * @Route("/demands/{id}/edit", name="demands_edit")
     */
    public function edit(Demand $demand, Request $request, ObjectManager $manager){

        $form = $this->createForm(DemandType::class, $demand);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($demand);
            $manager->flush();

            $this->addFlash(
                'success', 'Les modifications apportées à la demande ont bien été enregistrées !'
            );

            return $this->redirectToRoute('demands_show',[
                'id' => $demand->getId()
            ]);
        }


        return $this->render('demand/edit.html.twig', [
            'form' => $form->createView(),
            'demand' => $demand
        ]);
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
