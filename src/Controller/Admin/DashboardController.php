<?php

namespace App\Controller\Admin;

use App\Controller\CompanyController;
use App\Entity\Admin;
use App\Entity\Company;
use App\Entity\Streamer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController {

	//TODO: Gérer les droits d'accès

	#[Route('/admin', name: 'admin')]
	public function index(): Response
	{
		 return $this->render('admin/dashboard.html.twig', [
			 'page_title' => 'Dashboard',
		 ]);
	}

	public function configureDashboard(): Dashboard
	{
		return Dashboard::new()
		                ->setTitle('Symfony');
	}

	public function configureMenuItems(): iterable
	{
		yield MenuItem::linkToRoute('Dashboard', 'fa fa-table-columns', 'admin');
		yield MenuItem::linkToCrud(
			'Streamers',
			'fas fa-headset',
			Streamer::class
		);
		yield MenuItem::linkToCrud(
			'Compagnies',
			'fas fa-building',
			Company::class
		);
		yield MenuItem::linkToCrud(
			'Administrateurs',
			'fas fa-lock',
			Admin::class
		);
	}

}
