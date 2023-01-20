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
    #[Route('/streamer/profile', name: 'app_streamer_profile')]
    public function profile(): Response
    {
        return $this->render('streamer/profile.html.twig', [
            'controller_name' => 'Profile',
        ]);
    }

    #[Route('/search_page', name: 'app_search')]
    public function search(): Response
    {
        return $this->render('search_page/search_page', [
            'controller_name' => 'search',
        ]);
    }
}
