<?php

namespace App\Controller;

use App\Repository\DemandRepository;
use App\Repository\ProviderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProviderRepository $providerRepository, DemandRepository $demandRepository)
    {
        return $this->render('home/index.html.twig', [
            'providers' => $providerRepository->findBestProviders(4),
            'demands'   => $demandRepository->findLastDemands(4)
        ]);

    }
}
