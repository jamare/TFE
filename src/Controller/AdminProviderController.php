<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Entity\Province;
use App\Repository\ProviderRepository;
use App\Utils\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProviderController extends AbstractController
{
    /**
     * @Route("/admin/providers/{page}", name="admin_providers_index", requirements={"page": "\d+"}))
     */
    public function index(ProviderRepository $repo, $page = 1, Pagination $pagination)
    {
        $pagination->setEntityClass(Provider::class)
                   ->setPage($page);

        return $this->render('admin/provider/index.html.twig', [
            'providers' => $pagination->getData(),
            'pages' => $pagination->getPages(),
            'page' => $page
        ]);
    }

    /**
     * Permet de supprimer un prestataire
     *
     * @Route("/admin/provider/{id}/delete", name="admin_provider_delete")
     *
     * @param Provider $provider
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Provider $provider, ObjectManager $manager){
        $manager->remove($provider);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le prestataire a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_providers_index');
    }

}
