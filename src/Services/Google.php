<?php
/**
 * Created by PhpStorm.
 * User: nuand
 * Date: 19/03/2019
 * Time: 12:08
 */

namespace App\Services;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

class Google extends AbstractController
{
    public function sessionRead(Session $session)
    {
        $client = new \Google_Client();
        $client->setAuthConfig('../config/client_secret.json');
        $client->setAccessType('offline');
        $client->addScope(\Google_Service_YouTube::YOUTUBE_READONLY);
        $client->setAccessToken($session->get('access_token'));

        if ($client->isAccessTokenExpired()){
            $client->refreshToken($client->getRefreshToken());
            $session->set('access_token', $client->refreshToken($client->getRefreshToken()));
        }

        return $client;
    }

    public function sessionAdmin(Session $session)
    {
        $client = new \Google_Client();
        $client->setAuthConfig('../config/client_secret.json');
        $client->setAccessType('offline');
        $client->addScope(\Google_Service_YouTube::YOUTUBE);
        $client->setAccessToken($session->get('admin_token'));

        if ($client->isAccessTokenExpired()){
            $client->refreshToken($client->getRefreshToken());
            $session->set('access_token', $client->refreshToken($client->getRefreshToken()));
        }

        return $client;
    }
}