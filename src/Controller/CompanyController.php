<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    #[Route('/company', name: 'app_company')]
    public function index(): Response
    {
        return $this->render('company/company.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }

    #[Route('/company/profile', name: 'app_company_profile')]
    public function profile(): Response
    {
        return $this->render('company/profile.html.twig', [
            'controller_name' => 'profile',
        ]);
    }

    #[Route('/company/search', name: 'app_company_search')]
    public function search(): Response
    {
        return $this->render('search_page/search_page.html.twig', [
            'controller_name' => 'search',
        ]);
    }


}
