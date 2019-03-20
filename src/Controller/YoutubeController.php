<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Emission;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Services\Google;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class YoutubeController extends AbstractController
{

    /**
     * @Route("/youtube", name="youtube")
     */
    public function index(Session $session, Google $google)
    {

        return $this->render('visitor/yt_replay.html.twig', [
//            'controller_name' => 'YoutubeController',
//            'session' => $session,
//            'toto' => $toto,
        ]);
    }

//    remplir bdd avec nom des playlists sur yt

    /**
     * @Route("/youtube/category", name="yt_category")
     */
    public function ytCategory(Session $session, Google $google)
    {
        $service = new \Google_Service_YouTube($google->session($session));
        $toto = $service->playlists->listPlaylists('snippet',array(
            'channelId' => 'UCpmx1aRBERfIuNFJPmGnFZQ',
            'maxResults' => 25))->getItems();

//        Pour remplir la table catégorie avec les catégories des playlists
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($toto as $item => $value) {
            $category =  new Category();
            $prout = $value['snippet'];
            $prout['title'];
            echo $prout['title']."<hr>";
            $category->setType($prout['title']);
            $entityManager->persist($category);
            var_dump($category);
            $entityManager->flush();
        }
//        dd('fini');
        return $this->render('visitor/yt_replay.html.twig', [
//            'controller_name' => 'YoutubeController',
            'session' => $session,
            'toto' => $toto,
        ]);
    }


//    remplir bdd avec le contenu d'une playlist sur yt

    /**
     * @Route("/youtube/emission", name="yt_emission")
     */
    public function ytEmission(Session $session, Google $google)
    {
        $service = new \Google_Service_YouTube($google->session($session));
        $toto = $service->playlistItems->listPlaylistItems('snippet,contentDetails',array(
            'playlistId' => 'PLTWd_veB5ZqNcg9HX5efWQMUCpGsvBSwq',
            'maxResults' => 25))->getItems();
//        dd($toto);
//        Pour remplir la table catégorie avec les catégories des playlists
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($toto as $item => $value) {
            $emission =  new Emission();
            $prout = $value['snippet'];
            $caca = $value['contentDetails'];
            $prout['title'];
//            dd($value);
            $caca['videoId'];
            echo 'Titre:' . $prout['title']. '.....video id :'.$caca['videoId']. "<hr>";
            $emission->setTitle($prout['title']);
            $emission->setResume($prout['description']);
            $emission->setLien($caca['videoId']);
//            $category->getIdCategory();
            var_dump($emission);
//            dd($emission);
            $entityManager->persist($emission);
            $entityManager->flush();
        }
        dd('fini');
        return $this->render('visitor/yt_replay.html.twig', [
//            'controller_name' => 'YoutubeController',
            'session' => $session,
            'toto' => $toto,
        ]);
    }

    /**
     * @Route("youtube/token", name="yt_token")
     */
    public function getClient(Session $session): Response
    {
        $client = new \Google_Client();
        $client->setAuthConfig('../config/client_secret.json');
        $client->setAccessType('offline');
//        $client->setIncludeGrantedScopes(true);
        $client->addScope(\Google_Service_YouTube::YOUTUBE_READONLY);
        $client->setRedirectUri('http://'. $_SERVER['HTTP_HOST'] .'/youtube/code');
        $auth_url = $client->createAuthUrl();
        return $this->redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
    }

    /**
     * @Route("youtube/code", name="yt_code")
     */
    public function getCode(Session $session): Response
    {
        $session->start();
        $client = new \Google_Client();
        $client->setAuthConfig('../config/client_secret.json');
        $client->setRedirectUri('http://'. $_SERVER['HTTP_HOST'] .'/youtube/code');
        $client->addScope(\Google_Service_YouTube::YOUTUBE_READONLY);

        if (! isset($_GET['code'])) {
            $auth_url = $client->createAuthUrl();
            var_dump($_GET);
            dd('code vide');
            return $this->redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
        } else {
            $client->fetchAccessTokenWithAuthCode($_GET['code']);
//            var_dump($client);
            $token = $client->getAccessToken();
            $session->set('access_token', $token);
//            return $this->render('youtube/index.html.twig', ['session' => $client, 'token'=> $token]);
            return $this->redirectToRoute('youtube');
        }
    }
}
