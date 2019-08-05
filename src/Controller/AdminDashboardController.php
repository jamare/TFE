<?php

namespace App\Controller;

use App\Utils\StatsService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, StatsService $statsService)
    {

        $stats = $statsService->getStats();
        $bestProviders = $statsService->getProvidersStats('DESC');
        $worstProviders = $statsService->getProvidersStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestProviders' => $bestProviders,
            'worstProviders'=> $worstProviders
        ]);
    }
}
