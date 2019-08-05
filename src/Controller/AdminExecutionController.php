<?php

namespace App\Controller;

use App\Entity\Execution;
use App\Form\AdminExecutionType;
use App\Repository\ExecutionRepository;
use App\Utils\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminExecutionController extends AbstractController
{
    /**
     * @Route("/admin/executions/{page}", name="admin_executions_index", requirements={"page": "\d+"})
     */
    public function index(ExecutionRepository $repo, $page = 1, Pagination $pagination)
    {
        $pagination->setEntityClass(Execution::class)
                   //->setLimit(5)
                   ->setPage($page);

        return $this->render('admin/execution/index.html.twig', [
            'executions' => $pagination->getData(),
            'pages' => $pagination->getPages(),
            'page' => $page
        ]);
    }

    /**
     * Permet d'éditer une exécution de service
     *
     * @Route("/admin/executions/{id}/edit", name="admin_execution_edit")
     *
     * @return Response
     */
    public function edit(Execution $execution, Request $request, ObjectManager $manager){
        $form = $this->createForm(AdminExecutionType::class, $execution);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($execution);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'exécution de service n°{$execution->getId()} a bien été modifiée !"
            );

            return $this->redirectToRoute('admin_executions_index');
        }

        return $this->render('admin/execution/edit.html.twig',[
            'form' => $form->createView(),
            'execution' => $execution
        ]);
    }

    /**
     * Permet de supprimer une exécution de service
     *
     * @Route("/admin/executions/{id}/delete", name="admin_execution_delete")
     *
     * @return Response
     */
    public function delete(Execution $execution, ObjectManager $manager){
        $manager->remove($execution);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'exécution de service a bien été supprimé !"
        );
        return $this->redirectToRoute("admin_executions_index");
    }

}
