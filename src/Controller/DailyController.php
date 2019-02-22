<?php
/**
 * Created by PhpStorm.
 * User: nuand
 * Date: 06/02/19
 * Time: 11:36
 */

namespace App\Controller;

use App\Form\DailyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/daily/add", name="add_daily", methods={"GET|POST"})
     */
    public function addDaily(Request $request): Response
    {
        $api = new \Dailymotion();
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

        $pipi = $api->get(
            "/file/upload"
        );
        echo 'api file upload';
        $apiurl = ($pipi['upload_url']);
        var_dump($pipi['upload_url']);
        $form = $this->createForm(DailyType::class);
        $form->handleRequest($request);
        $lien = '';
        $titre = '';
        echo 'api';

        var_dump($api);


        if ($form->isSubmitted())
        {

            $file = $form['file']->getData();
            $title = $form['title']->getData();
            $resume = $form['resume']->getData();

            echo 'file';
            var_dump($file);
            $data = $form->getData('pathName');
            echo 'data';
            var_dump($data);

            $url = $api->uploadFile($file);
            echo 'url';
            var_dump($url);
            $result = $api->post(
                '/me/videos',
                array('url' => $url, 'title' => $title, 'channel'=> 'videogames', 'tags'=> $resume,'published' => true)
            );
            var_dump($result);
            $list = $this->connection()->get("/me/videos?limit=20",
                array('fields' => array('id', 'title', 'owner', 'embed_url', 'tag', 'embed_html','updated_time'))
            );
            return $this->render('emission/index_daily.html.twig', ['list'=> $list, "text" => $result]);
        }


            return $this->render('daily/add_daily.html.twig', ['form'=> $form->createView(), "text" => "encore rien envoyé"]);
        }

//    }

    /**
    * @Route("/daily/show", name="show_daily", methods={"GET"})
    */

        public function showAll(): Response
        {
            $list = $this->connection()->get("/me/videos?limit=20",
                array('fields' => array('id', 'title', 'owner', 'embed_url', 'tag', 'embed_html','updated_time'))
            );

            return $this->render('emission/index_daily.html.twig', ['list'=> $list]);
        }
}