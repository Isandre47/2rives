<?php
/**
 * Created by PhpStorm.
 * User: nuand
 * Date: 06/02/19
 * Time: 11:36
 */

namespace App\Controller;

use App\Form\DailyType;
use App\Form\UploadType;
use App\Services\Daily;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DailyController extends AbstractController
{

    private function connection()
    {
        $api = new \Dailymotion();


        return $api->setGrantType(
            \Dailymotion::GRANT_TYPE_PASSWORD,
            $_ENV['API_KEY'],
            $_ENV['API_SECRET'],
            array(),
            array(
                'username' => $_ENV['username'],
                'password' => $_ENV['password']
            )
        );
    }

    /**
     * @Route("/daily", name="daily", methods={"GET"})
     */
    public function dailyM(): Response
    {
        $api = new \Dailymotion();

        $limit = 12;

        $idvideo = 'x720cah';

        $api->setGrantType(
            \Dailymotion::GRANT_TYPE_PASSWORD,
            $_ENV['API_KEY'],
            $_ENV['API_SECRET'],
            array(),
            array(
                'username' => $_ENV['username'],
                'password' => $_ENV['password']
            )
        );

            $result = $api->get(
                "/me/videos?limit=$limit",
                array('fields' => array('id', 'title', 'owner', 'embed_url', 'tag', 'embed_html'))
            );

            $editone = $api->get(
                "/video/$idvideo",
                array('fields' => array('id', 'title', 'owner', 'embed_url', 'tag', 'embed_html'))
            );

            $auth = $api->get(
                '/auth',
                array('fields' => array('id', 'scope', 'roles', 'username', 'screenname', 'language'))
            );
            return $this->render('daily/daily.html.twig', ['video'=> $result, 'id_user'=> $auth, 'idone'=> $editone]);
    }



    /**
     * @Route("/homepage", name="homepage", methods={"GET"})
     */
    public function indexAction()
    {
        $form = $this->createForm(UploadType::class);
        return $this->render('daily/upload.html.twig', [ 'form' => $form->createView()]);
    }


    /**
     * @Route("/upload", name="upload", methods={"POST"})
     */
    public function uploadAction(Request $request): Response
    {

        function decode_chunk($data) {
            $data = explode(';base64,', $data);

            if (!is_array($data) || !isset($data[1])) {
                return false;
            }

            $data = base64_decode($data[1]);
            if (!$data) {
                return false;
            }

            return $data;
        }

// $file_path: fichier cible: garde le même nom de fichier, dans le dossier uploads
        $file_path = 'upload/' . $_POST['file'];
        $file_data = decode_chunk($_POST['file_data']);

        if (false === $file_data) {
            echo "error";
        }

        /* on ajoute le segment de données qu'on vient de recevoir
         * au fichier qu'on est en train de ré-assembler: */
        file_put_contents($file_path, $file_data, FILE_APPEND);

// nécessaire pour que JavaScript considère que la requête s'est bien passée:
        return JsonResponse::create([]);
    }


    /**
     * @Route("/bigfile", name="bigfile", methods={"GET|POST"})
     */
    public function bigDaily(Request $request, Daily $daily): Response
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

//            $url = $daily->connection()->uploadFile($file);
//            echo 'url';
//            var_dump($url);
//            $result = $daily->connection()->post(
//                '/me/videos',
//                array('url' => $url, 'title' => $title, 'channel'=> 'videogames', 'tags'=> $resume,'published' => true)
//            );


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


}