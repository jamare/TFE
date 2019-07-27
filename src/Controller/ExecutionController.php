<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Demand;
use App\Entity\Execution;
use App\Form\CommentType;
use App\Form\ExecutionType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExecutionController extends AbstractController
{
    /**
     * @Route("/demands/{id}/execution", name="execution_create")
     */
    public function execution(Demand $demand, Request $request, ObjectManager $manager)
    {
        $execution = new Execution();
        $form = $this->createForm(ExecutionType::class, $execution);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $provider = $this->getUser();

            $execution->setProvider($provider)
                      ->setDemand($demand);

            $manager->persist($execution);
            $manager->flush();

            return $this->redirectToRoute('execution_show', ['id' => $execution->getId()]);
        }

        return $this->render('execution/execution.html.twig', [
            'demand' => $demand,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'une réservation
     *
     * @Route("/execution/{id}", name="execution_show")
     *
     * @param Execution $execution
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function show(Execution $execution, Request $request, ObjectManager $manager){

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setProvider($execution->getProvider())
                    ->setAuthor($this->getUser());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a bien été enregistré !"
            );
        }

        return $this->render('execution/show.html.twig', [
            'execution' => $execution,
            'form' => $form->createView()
        ]);
    }
}
