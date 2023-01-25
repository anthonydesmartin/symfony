<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Streamer;
use App\Form\RegistrationFormType;
use App\Repository\CompanyRepository;
use App\Repository\ContractsRepository;
use App\Repository\StreamerRepository;
use App\Security\StreamerAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StreamerController extends AbstractController
{
    #[Route('/streamer', name: 'app_streamer')]
    public function index(): Response
    {
        return $this->render('streamer/streamer.html.twig', [
            'controller_name' => 'StreamerController',
            'pp' => $this->getUser()->getProfilePicture(),
        ]);
    }

    #[Route('/streamer/profile', name: 'app_streamer_profile')]
    public function profile(): Response
    {
        $streamerinfo = $this->getUser();
        $streamerinfo = [
            'Pseudo' => $streamerinfo->getUsername(),
            'Mail' => $streamerinfo->getMail(),
            'Siret' => $streamerinfo->getSiret(),
            'Followers' => $streamerinfo->getFollowers(),
            'Public' => $streamerinfo->isIsMature() ? 'true' : 'false',
            'StreamerID' => $streamerinfo->getIdStreamer(),
        ];
        $missing_info = [];


        foreach ($streamerinfo as $key => $value) {
            if ($value === null) {
                $missing_info[] = $key;
            }
        }

        return $this->render('streamer/profile.html.twig', [
            'missing_info' => $missing_info,
            'pp' => $this->getUser()->getProfilePicture(),
        ]);
    }


    #[Route('/streamer/profile/edit', name: 'app_streamer_profile_edit')]
    public function edit(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        StreamerAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        HttpClientInterface $client
    ): Response {
        function clearHeaders()
        {
            header_remove('Authorization');
            header_remove('Client-Id');
            header_remove('ContentType');
        }

        $streamer = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $streamer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $streamer->setPassword(
                $userPasswordHasher->hashPassword(
                    $streamer,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($streamer);
            $entityManager->flush();

            return $this->redirectToRoute('app_streamer_profile');
        }

        return $this->render('registration/register.html.twig', [
            'title' => 'Modifier mon profil',
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/streamer/search/profile/{id}', name: 'app_show_company')]
    public function show_profile(Company $company): Response
    {
        $missing_info_company = [];
        $missing_info_streamer = [];
        $streamer = $this->getUser();
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
                $missing_info_company[] = $key;
            }
        }
        foreach ($streamer_info as $key => $value) {
            if ($value === null) {
                $missing_info_streamer[] = $key;
            }
        }

        return $this->render('search_page/show_profile.html.twig', [
            'company' => $company,
            'pp' => $this->getUser()->getProfilePicture(),
            'missing_info_company' => $missing_info_company,
            'missing_info_streamer' => $missing_info_streamer,
        ]);
    }

    #[Route('/streamer/contract', name: 'app_streamer_contract')]
    public function contract(ContractsRepository $contractsRepo, CompanyRepository $companyRepo): Response
    {
        $contracts = $contractsRepo->findBy(['streamer' => $this->getUser()]);
        foreach ($contracts as $contract) {
            $contract->getCompany()->getSiret();
        }

        return $this->render('contract/contract.html.twig', [
            'contracts' => $contracts,
        ]);
    }

    #[Route('/streamer/profile/refresh', name: 'app_streamer_profile_refresh')]
    public function refresh(RegistrationController $registrationController, StreamerRepository $streamerRepo, HttpClientInterface
    $client, EntityManagerInterface $entityManager):
    Response
    {
        $streamer = $this->getUser()->getUserIdentifier();
        $user = $streamerRepo->findOneBy(['username' => $streamer]);
        $streamerpp = $registrationController->getStreamerTwitchIdAndPp($user->getIdStreamer(),
	        $client);
        $streamerfollowers =
	        $registrationController->getStreamerTwitchFollowers($client,
		        $user->getIdStreamer());
        $user->setProfilePicture($streamerpp['profile_image_url']);
        $user->setFollowers($streamerfollowers);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_streamer_profile');
    }

	#[Route('/refreshAll', name: 'app_streamer_profile_refresh_all')]
	public function refreshAll(RegistrationController $registrationController,
		StreamerRepository $streamerRepo, HttpClientInterface
	$client, EntityManagerInterface $entityManager):
	Response
	{
		$streamers = $streamerRepo->findAll();
		set_time_limit(0);
		foreach ($streamers as $streamer){
			$streamerpp = $registrationController->getStreamerTwitchIdAndPp
			($streamer->getIdStreamer(), $client);
			$streamerfollowers = $registrationController->getStreamerTwitchFollowers($client, $streamer->getIdStreamer());
			$user = $streamerRepo->findOneBy(['username' => $streamer->getUsername()]);
			$user->setProfilePicture($streamerpp['profile_image_url']);
			$user->setFollowers($streamerfollowers);
			$entityManager->persist($user);
			$entityManager->flush();
		}

		return $this->redirectToRoute('admin');
	}


}
