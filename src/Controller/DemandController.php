<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Form\DemandType;
use App\Repository\DemandRepository;
use App\Utils\UploaderHelper;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager, UploaderHelper $uploaderHelper){

        $demand = new Demand();

        $form = $this->createForm(DemandType::class, $demand);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['logoFilename']->getData();

            if($uploadedFile){
                $newFilename = $uploaderHelper->uploadDemandFrontImage($uploadedFile);
                $demand->setImageFront($newFilename);
            }

            $demand->setCustomer($this->getUser());
            $manager->persist($demand);
            $manager->flush();

            $this->addFlash(
                'success', 'La demande a bien été enregistrée !'
            );

            return $this->redirectToRoute('demands_index',[
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
     * @Security("is_granted('ROLE_USER') and user === demand.getCustomer()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
     *
     * @return Response
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

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/demands/{id}/delete", name="demands_delete")
     * @Security("is_granted('ROLE_USER') and user == demand.getCustomer()", message="Vous n'avez pas le droit d'accéder à cette ressource")
     *
     * @param Demand $demand
     * @param ObjectManager $manager
     * @return Response
     *
     */
    public function delete(Demand $demand, ObjectManager $manager){
        $manager->remove($demand);
        $manager->flush();

        $this->addFlash(
            'success', "L'annonce <strong>{$demand->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("demands_index");
    }

}
