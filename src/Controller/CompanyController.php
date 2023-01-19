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
}
