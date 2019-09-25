<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\AdminCategoryType;
use App\Repository\CategoryRepository;
use App\Utils\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/category/{page}", name="admin_category_index", requirements={"page": "\d+"}))
     */
    public function index(CategoryRepository $repo, $page = 1, Pagination $pagination)
    {
        $pagination->setEntityClass(Category::class)
            ->setPage($page);

        return $this->render('admin/category/index.html.twig', [
            'categories' => $pagination->getData(),
            'pages' => $pagination->getPages(),
            'page' => $page
        ]);
    }

    /**
     * Permet d'ajouter une catégorie
     *
     * @Route("/admin/category/new", name="admin_category_create")
     *
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager){
        $category = new Category();

        $form = $this->createForm(AdminCategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success', 'La catégorie a bien été enregistrée !'
            );
        }
        return $this->render('admin/category/add.html.twig',[
            'category' => $category,
            'form' => $form->createView()
        ]);

    }

    /**
     * Permet de modifier une catégorie
     *
     * @Route("/admin/category/{id}/edit", name="admin_category_edit")
     *
     * @return Response
     */
    public function edit(Category $category, Request $request, ObjectManager $manager){

        $form = $this->createForm(AdminCategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                "La catégorie n°{$category->getId()} a bien été modifiée !"
            );
        }


        return $this->render('admin/category/edit.html.twig',[
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une catégorie
     *
     * @Route("/admin/category/{id}/delete", name="admin_category_delete")
     *
     * @param Category $category
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Category $category, ObjectManager $manager){
        $manager->remove($category);
        $manager->flush();

        $this->addFlash(
            'success',
            "La catégorie a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_category_index');
    }

}
