<?php

namespace App\Controller;

use App\Entity\Emission;
use App\Form\EmissionType;
use App\Form\UploadType;
use App\Repository\CategoryRepository;
use App\Repository\EmissionRepository;
use App\Services\Google;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class YtAdminController extends AbstractController
{
    /**
     * @Route("/yt/admin", name="yt_admin")
     */
    public function index(EmissionRepository $emissionRepository, CategoryRepository $categoryRepository)
    {
        $emmission = $emissionRepository->allWithCategory();
        $category = $categoryRepository->allWithEmission();

        return $this->render('yt_admin/index.html.twig', [
            'controller_name' => 'YtAdminController',
            'emissions' => $emmission,
            'category' => $category,
        ]);
    }

    /**
     * @Route("/yt/upload", name="yt_upload", methods={"GET|POST"})
     */
    public function upload(Session $session, Google $google, Request $request)
    {
        $emission = new Emission();

        $form = $this->createForm(EmissionType::class, $emission);
        $client = new \Google_Client();
        $client->setAuthConfig('../config/client_secret.json');
        $client->addScope(\Google_Service_YouTube::YOUTUBE);
        $client->setAccessToken($session->get('admin_token'));

        $youtube = new \Google_Service_YouTube($client);
//        $form2 =  $this->createForm(UploadType::class);
        $htmlBody = '';
//        $form2->handleRequest($request);
//        dd($client);
//        dd($youtube);
        if ($request->isMethod('POST')){


//            dd($_FILES);
//            var_dump($_FILES['file']);
            $file = $_FILES['file'];
            $videoPath = $file['tmp_name'];
//            $upload = new UploadedFile();
            var_dump($file, $videoPath);
            $snippet = new \Google_Service_YouTube_VideoSnippet();
            $snippet->setTitle('Test title');
            $snippet->setDescription('Test description');
            $snippet->setTags(['tab', 'tag2']);
            $snippet->setCategoryId('22');

            $status = new \Google_Service_YouTube_VideoStatus();
            $status->setPrivacyStatus = "public";

            $video = new \Google_Service_YouTube_Video();
            $video->setSnippet($snippet);
            $video->setStatus($status);

            $chunkSizeBytes = 1 * 1024 * 1024;

            $client->setDefer(true);

            $insertRequest = $youtube->videos->insert("status,snippet", $video);

            $media = new \Google_Http_MediaFileUpload(
                $client,
                $insertRequest,
                'video/*',
                null,
                true,
                $chunkSizeBytes
            );
            $media->setFileSize(filesize($videoPath));

            $status = false;
            $handle = fopen($videoPath, "rb");
            while (!$status && !feof($handle)) {
                $chunk = fread($handle, $chunkSizeBytes);
                $status = $media->nextChunk($chunk);
            }

            fclose($handle);

            $htmlBody .= "<h3>Video Uploaded</h3><ul>";
            $htmlBody .= sprintf('<li>%s (%s)</li>',
                $status['snippet']['title'],
                $status['id']);

            $htmlBody .= '</ul>';


        }

        return $this->render('yt_admin/emission/new.html.twig', [
//            'form'=> $form->createView(),
            "text" => "encore rien envoyé",
            "text" => $htmlBody,
//            'form2'=> $form2->createView()
        ]);

    }

    /**
     * @Route("/yt/js", name="yt_uploadpost")
     */
    public function uploadPost(Request $request, Session $session)
    {
        return $this->render('yt_admin/emission/uploadjs.html.twig', ['session' => $session]);

    }

}
