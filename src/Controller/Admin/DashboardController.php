<?php

namespace App\Controller\Admin;

use App\Controller\CompanyController;
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
//		$adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
//		return $this->redirect($adminUrlGenerator->setController(StreamerCrudController::class)
//		                                         ->generateUrl());

		// Option 2. You can make your dashboard redirect to different pages depending on the user
		//
		// if ('jane' === $this->getUser()->getUsername()) {
		//     return $this->redirect('...');
		// }

		// Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
		// (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
		//
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
		yield MenuItem::linkToRoute('Accueil', 'fa fa-home', 'home_page');
		yield MenuItem::linkToRoute('Dashboard', 'fa fa-table-columns', 'admin');
		yield MenuItem::linkToCrud(
			'Streamers',
			'fas fa-map-marker-alt',
			Streamer::class
		);
		yield MenuItem::linkToCrud(
			'Compagnies',
			'fas fa-comments',
			Company::class
		);
	}

}
