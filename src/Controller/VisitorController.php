<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Emission;
use App\Repository\CategoryRepository;
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
     * @Route("/replay", name="replay_all")
     */
    public function indexReplay(EmissionRepository $emissionRepository, CategoryRepository $categoryRepository):  Response
    {
        $emmission = $emissionRepository->allWithCategory();
        $category = $categoryRepository->allWithEmission();
//        dd($emmission);
        return $this->render('visitor/replay.html.twig', [
            'controller_name' => 'VisitorController',
            'emission' => $emmission,
            'category' => $category,
        ]);
    }


    /**
     * @Route("/replay/{id}", name="replay_id")
     */
    public function indexReplayByCat(EmissionRepository $emissionRepository, CategoryRepository $categoryRepository, $id):  Response
    {
        $emmission = $emissionRepository->byCategory($id);
        $category = $categoryRepository->allWithEmission();
//        dd($emmission);
        return $this->render('visitor/replayid.html.twig', [
            'controller_name' => 'VisitorController',
            'emission' => $emmission,
            'category' => $category,
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
