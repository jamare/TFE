<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Repository\ProviderRepository;
use App\Utils\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
