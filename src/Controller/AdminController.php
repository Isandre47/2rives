<?php
/**
 * Created by PhpStorm.
 * User: nuand
 * Date: 08/02/2019
 * Time: 16:38
 */

namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Repository\EmissionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/bo", name="bo_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository, EmissionRepository $emissionRepository, UserRepository $userRepository): Response
    {
        return $this->render('bo.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'emissions' => $emissionRepository->findAll(),
            'user' => $userRepository->findAll()
        ]);
    }
}