<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Repository\DemandRepository;
use App\Repository\ProvinceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return Response
     */
    public function requestPerso(Request $request)
    {
        /** @var DemandRepository $repository */
        $search_province = $request->get('search_province');
        $search_category = $request->get('search_category');

        $repository = $this->getDoctrine()->getRepository(Demand::class);
        $demands = $repository->searchDemandByCategoryProvince($search_province, $search_category);


        return $this->render('search/search_result.html.twig', [
            'demands' => $demands,
        ]);
    }
}
