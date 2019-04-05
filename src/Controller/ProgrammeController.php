<?php
/**
 * Created by PhpStorm.
 * User: nuand
 * Date: 26/03/2019
 * Time: 10:17
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ProgrammeController extends AbstractController
{

    /**
     * @Route("/programme", name="programme")
     */
    public function indexProgramme()
    {
        return $this->render('visitor/construction.html.twig');
    }

    /**
     * @Route("/programme/{id}", name="jour")
     */
    public function indexJour($id)
    {
        return $this->render('visitor/'. $id. '.html.twig', [
//            'category'=> $categoryRepository->findAll(),
        ]);
    }
}