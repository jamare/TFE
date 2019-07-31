<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Form\DemandType;
use App\Repository\DemandRepository;
use App\Utils\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/demands/{page}", name="admin_demands_index", requirements={"page": "\d+"})
     */
    public function index(DemandRepository $repo, $page = 1, Pagination $pagination)
    {

       $pagination->setEntityClass(Demand::class)
                  ->setPage($page);

        return $this->render('admin/demand/index.html.twig', [
                'demands' => $pagination->getData(),
                'pages' => $pagination->getPages(),
                'page' => $page
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/demands/{id}/edit", name="admin_demands_edit")
     *
     * @param Demand $demand
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Demand $demand, Request $request, ObjectManager $manager){
        $form = $this->createForm(DemandType::class, $demand);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($demand);
            $manager->flush();

            $this->addFlash(
                'success',
                "La demande <strong>{$demand->getTitle()}</strong> a bien été enregistrée !"
            );
        }

        return $this->render('admin/demand/edit.html.twig', [
           'demand' => $demand,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/demands/{id}/delete", name="admin_demands_delete")
     *
     * @param Demand $demand
     * @param ObjectManager $manager
     * @return Response
     *
     */
    public function delete(Demand $demand, ObjectManager $manager){

        if(count($demand->getExecutions()) > 0){
            $this->addFlash(
              'warning',
              "Vous ne pouvez pas supprimer la demande <strong>{$demand->getTitle()}</strong> car elle possède des prestataires connectés"
            );
        }else{
            $manager->remove($demand);
            $manager->flush();

            $this->addFlash(
                'success',
                "La demande a bien été supprimée"
            );
        }
        return $this->redirectToRoute('admin_demands_index');
    }
}
