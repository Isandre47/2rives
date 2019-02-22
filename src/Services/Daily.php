<?php
/**
 * Created by PhpStorm.
 * User: nuand
 * Date: 20/02/2019
 * Time: 14:05
 */

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Daily extends AbstractController
{

    public function connection()
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
}