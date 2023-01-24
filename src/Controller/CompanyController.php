<?php

namespace App\Controller;


use App\Entity\Streamer;
use App\Form\RegistrationFormType;


use App\Repository\ContractsRepository;

use App\Repository\CategoriesRepository;
use App\Repository\CompanyRepository;

use App\Security\StreamerAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\StreamerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class CompanyController extends AbstractController
{
    #[Route('/company', name: 'app_company')]
    public function index(StreamerRepository $streamerRepo): Response
    {
        $streamers = $streamerRepo->getTopStreamers();

        return $this->render('company/company.html.twig', [
            'controller_name' => 'CompanyController',
            'streamers'=> $streamers
        ]);
    }

    #[Route('/company/profile', name: 'app_company_profile')]
    public function profile(): Response
    {
        $companyinfo = $this->getUser();
        $companyinfo = [
            'Name' => $companyinfo->getName(),
            'Mail' => $companyinfo->getMail(),
            'Siret' => $companyinfo->getSiret(),
            'Head_Office' => $companyinfo->getHeadOffice(),
            'Register' => $companyinfo->getRegister(),
            'Description' => $companyinfo->getDescription()
        ];
        $missing_info = [];


        foreach ($companyinfo as $key => $value) {
            if ($value === null) {
                $missing_info[] = $key;
            }
        }

        return $this->render('company/profile.html.twig', [
            'controller_name' => 'profile',
            'missing_info' => $missing_info
        ]);
    }

    #[Route('/company/profile/edit', name: 'app_company_profile_edit')]
    public function edit(Request $request,UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, StreamerAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $company->setPassword(
                $userPasswordHasher->hashPassword(
                    $company,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($company);
            $entityManager->flush();
            return $this->redirectToRoute('app_company_profile');
        }
        return $this->render('registration/register.html.twig', [
            'title' => 'Modifier mon profil',
            'registrationForm' => $form->createView(),
        ]);
    }





    #[Route('/company/search', name: 'app_company_search')]
    public function getPaginatorStreamer(StreamerRepository $streamerRepository, CategoriesRepository $categoriesRepository, Request $request): Response
    {
        $username_search = $request->get('username_search', '');
        $game_search = $request->get('game_search', '');
        $offset= max(0, $request->get('offset', 0));
        $paginator = $streamerRepository->getPaginatorStreamer($offset, $username_search, $game_search);
        $games = $categoriesRepository->getListGame();


        return $this->render('search_page/search_page.html.twig', [
            'games' => $games,
            'streamers' => $paginator,
            'previous' => $offset - StreamerRepository::STREAMERS_PER_PAGE,
            'next' => min(count($paginator),$offset + StreamerRepository::STREAMERS_PER_PAGE),
            'username_search' => $username_search,
            'game_search' => $game_search,
        ]);
    }





    #[Route('/company/search/profile/{id}', name: 'app_show_streamer')]
    public function show_profile(Streamer $streamer): Response
    {
        $company = $this->getUser();
        $missing_info_company = false;
        $missing_info_streamer = false;
        $streamer_info = [
            'Mail' => $streamer->getMail(),
            'Siret' => $streamer->getSiret(),
        ];
        $company_info = [
            'Mail' => $company->getMail(),
            'head' => $company->getHeadOffice(),
            'register' => $company->getRegister(),
        ];

        foreach ($company_info as $key => $value) {
            if ($value === null) {
                $missing_info_company = true;
            }
        }
        foreach ($streamer_info as $key => $value) {
            if ($value === null) {
                $missing_info_streamer = true;
            }
        }
        return $this->render('search_page/show_profile.html.twig', [
            'streamer' => $streamer,
            'missing_info_company' => $missing_info_company,
            'missing_info_streamer' => $missing_info_streamer,
            'profile_picture' => $streamer->getProfilePicture()
        ]);
    }

    #[Route('/company/contract', name: 'app_company_contract')]
    public function contract(ContractsRepository $contractsRepo, CompanyRepository $companyRepo): Response
    {
        $contracts = $contractsRepo->findBy(['company' => $this->getUser()]);
        foreach ($contracts as $contract) {
            $contract->getStreamer()->getIdStreamer();
        }
        return $this->render('contract/contract.html.twig', [
            'contracts' => $contracts
        ]);
    }




}
