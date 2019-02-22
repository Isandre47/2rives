<?php

namespace App\Controller;

use App\Repository\EmissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitorController extends AbstractController
{
    /**
     * @Route("/", name="visitor")
     */
    public function index(EmissionRepository $emissionRepository):  Response
    {
        $emmission = $emissionRepository->allWithCategory();
        return $this->render('visitor/index.html.twig', [
            'controller_name' => 'VisitorController',
            'emission' => $emmission,
        ]);
    }

    /**
     * @Route("/replay", name="replay")
     */
    public function indexReplay(EmissionRepository $emissionRepository):  Response
    {
        $emmission = $emissionRepository->allWithCategory();
        return $this->render('visitor/replay.html.twig', [
            'controller_name' => 'VisitorController',
            'emission' => $emmission,
        ]);
    }

    /**
     * @Route("/live", name="live")
     */
    public function indexLive(EmissionRepository $emissionRepository):  Response
    {
        $live = $emissionRepository->allWithCategory();
        return $this->render('visitor/live.html.twig', [
            'controller_name' => 'VisitorController',
            'emission' => $live,
        ]);
    }
}
