<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StreamerController extends AbstractController
{
    #[Route('/streamer', name: 'app_streamer')]
    public function index(): Response
    {
        return $this->render('streamer/streamer.html.twig', [
            'controller_name' => 'StreamerController',
        ]);
    }
}
