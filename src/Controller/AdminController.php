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

    /**
     * @Route("/oldbo", name="oldbo")
     */
    public function oldBo()
    {
        $curl = curl_init('http://www.2rives.tv/Backoffice/destination/videos/');
//        $fp = fopen('index.php', 'w');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $toto = curl_exec($curl);
        $toto = json_decode($toto);
//        dd($toto);
        foreach ( $toto as $value => $key)
        {
            echo ($key['0']->name);
        }
//        var_dump($fp);

        dd($toto);
        return $this->render('oldbo.html.twig');
    }
}