<?php

namespace App\Controller;

use App\Entity\Province;
use App\Form\AdminProvinceType;
use App\Repository\ProvinceRepository;
use App\Utils\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProvincesController extends AbstractController
{
    /**
     * @Route("/admin/provinces/{page}", name="admin_provinces_index", requirements={"page": "\d+"})
     */
    public function index(ProvinceRepository $repo, $page = 1, Pagination $pagination)
    {
        $pagination->setEntityClass(Province::class)
            ->setPage($page);

        return $this->render('admin/provinces/index.html.twig', [
            'provinces' => $pagination->getData(),
            'pages' => $pagination->getPages(),
            'page' => $page
        ]);
    }

    /**
     * Permet d'ajouter une province
     *
     * @Route("/admin/province/new", name="admin_province_create")
     *
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager){
        $province = new Province();

        $form = $this->createForm(AdminProvinceType::class, $province);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($province);
            $manager->flush();

            $this->addFlash(
                'success', 'La province a bien été enregistrée !'
            );
        }
        return $this->render('admin/provinces/add.html.twig',[
            'province' => $province,
            'form' => $form->createView()
        ]);

    }

    /**
     * Permet de modifier une province
     *
     * @Route("/admin/province/{id}/edit", name="admin_province_edit")
     *
     * @return Response
     */
    public function edit(Province $province, Request $request, ObjectManager $manager){

        $form = $this->createForm(AdminProvinceType::class, $province);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($province);
            $manager->flush();

            $this->addFlash(
                'success',
                "La province {$province->getName()} a bien été modifiée !"
            );
        }


        return $this->render('admin/provinces/edit.html.twig',[
            'province' => $province,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une province
     *
     * @Route("/admin/province/{id}/delete", name="admin_province_delete")
     *
     * @param Province $province
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Province $province, ObjectManager $manager){
        $manager->remove($province);
        $manager->flush();

        $this->addFlash(
            'success',
            "La province a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_provinces_index');
    }
}
