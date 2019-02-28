<?php

namespace App\Controller;

use App\Entity\Emission;
use App\Form\EmissionType;
use App\Repository\EmissionRepository;
use App\Services\Daily;
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
    public function index(EmissionRepository $emissionRepository, Daily $daily): Response
    {
        $list = $daily->connection()->get("/me/videos?limit=40",
            array('fields' => array('id', 'title', 'owner', 'embed_url', 'tag', 'embed_html','updated_time')));
        $emmission = $emissionRepository->allWithCategory();
        return $this->render('emission/index.html.twig', [
            'emissions' => $emmission,
            'list' => $list,
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

        if ($form->isSubmitted()) {
//            dd($_FILES);
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
     * @Route("/add", name="ajout_d", methods={"GET|POST"})
     */
    public function addDaily(Request $request, Daily $daily): Response
    {
        $emission = new Emission();



        $pipi = $daily->connection()->get(
            "/file/upload"
        );

//        echo 'api file upload';
        $apiurl = ($pipi['upload_url']);
//        var_dump($pipi['upload_url']);
        $form = $this->createForm(EmissionType::class, $emission);
//        $form->handleRequest($request);
        $lien = '';
        $titre = '';
//        echo 'api';
//
//        var_dump($api);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
//            dd($form);
            $file = $form['lien']->getData();
            $title = $form['title']->getData();
            $resume = $form['resume']->getData();
//
//            echo 'file';
//            var_dump($file);
            $data = $form->getData('pathName');
//            echo 'data';
//            var_dump($data);

            $url = $daily->connection()->uploadFile($file);
//            echo 'url';
//            var_dump($url);
            $result = $daily->connection()->post(
                '/me/videos',
                array('url' => $url, 'title' => $title, 'channel'=> 'videogames', 'tags'=> $resume,'published' => true)
            );


            $list = $daily->connection()->get("/me/videos?limit=1",
                array('fields' => array('embed_url','id'))
            );

//            dd($request);
//            dd($list);
//            dd($emission);

            foreach ($list['list'] as $value=>$key){
                $lien =  $key['embed_url'];
                $idmedias = $key['id'];
            }

            $emission->setLien($lien);
            $emission->setMedias($idmedias);
//            dd($emission);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emission);
            $entityManager->flush();


//            var_dump($result);


            return $this->redirectToRoute('emission_index', ['list'=> $list]);
        }


        return $this->render('emission/new.html.twig', ['form'=> $form->createView(), "text" => "encore rien envoyé"]);
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
     * @Route("/{id}", name="emission_delete")
     */
    public function delete(Request $request, Emission $emission, Daily $daily, $id): Response
    {
//        dd($id);


        if ($this->isCsrfTokenValid('delete'.$emission->getId(), $request->request->get('_token'))) {
//            dd($emission);
            $daily->connection()->delete('/video/'. $emission->getMedias());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emission);
            $entityManager->flush();
            return $this->redirectToRoute('emission_index');
        }

        return $this->redirectToRoute('emission_index');
    }
}
