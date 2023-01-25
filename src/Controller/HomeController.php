<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_page')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_STREAMER')) {
            return $this->redirectToRoute('app_streamer');
        }
        if ($this->isGranted('ROLE_COMPANY')) {
            return $this->redirectToRoute('app_company');
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        }

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
