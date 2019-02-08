<?php

namespace App\Controller;

use App\Entity\Emission;
use App\Form\EmissionType;
use App\Repository\EmissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/emission")
 */
class EmissionController extends AbstractController
{
    /**
     * @Route("/", name="emission_index", methods={"GET"})
     */
    public function index(EmissionRepository $emissionRepository): Response
    {
        $emmission = $emissionRepository->allWithCategory();
        return $this->render('emission/index.html.twig', [
            'emissions' => $emmission,
        ]);
    }

    /**
     * @Route("/new", name="emission_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $emission = new Emission();
        $form = $this->createForm(EmissionType::class, $emission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $file = $emission->getMedias();
//            $fileName = md5(uniqid()).'.'.$file->guessExtension();
//            $file->move($this->getParameter('upload_medias_directory'), $fileName);
//            $emission->setMedias($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emission);
            $entityManager->flush();

            return $this->redirectToRoute('emission_index');
        }

        return $this->render('emission/new.html.twig', [
            'emission' => $emission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="emission_show", methods={"GET"})
     */
    public function show(Emission $emission, $id): Response
    {
        $emission2 = $this->getDoctrine()->getRepository(Emission::class)->find($id);
        $categoryName = $emission2->getCategory()->getType();
        return $this->render('emission/show.html.twig', [
            'emission' => $emission,
            'category' => $categoryName,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="emission_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Emission $emission): Response
    {
//        $oldmedias = $emission->getMedias();
        $form = $this->createForm(EmissionType::class, $emission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $data = $form->getData();
//            $file = $emission->getMedias();
//            $data->setMedias($file);
//            if ($form['medias']->getData() != null){
//                $fileName = md5(uniqid() .  '.' . $file->guessExtension());
//                $file->move($this->getParameter('upload_medias_directory'), $fileName);
//                $data->setMedias($fileName);
//            }else{
//                $emission->setMedias($oldmedias);
//            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('emission_index', [
                'id' => $emission->getId(),
            ]);
        }

        return $this->render('emission/edit.html.twig', [
            'emission' => $emission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="emission_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Emission $emission): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('emission_index');
    }
}
