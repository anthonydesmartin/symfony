<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Streamer;
use App\Form\RegistrationFormType;
use App\Controller\RegistrationController;
use App\Repository\CompanyRepository;
use App\Security\StreamerAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class StreamerController extends AbstractController
{
    #[Route('/streamer', name: 'app_streamer')]
    public function index(): Response
    {
        $pp = $this->getUser()->getProfilePicture();

        return $this->render('streamer/streamer.html.twig', [
            'controller_name' => 'StreamerController',
            'pp' => $pp,
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
            'StreamerID' => $streamerinfo->getIdStreamer()
        ];
        $missing_info = [];


        foreach ($streamerinfo as $key => $value) {
            if ($value === null) {
                $missing_info[] = $key;
            }
        }

        return $this->render('streamer/profile.html.twig', [
            'missing_info' => $missing_info
        ]);
    }



    #[Route('/streamer/profile/edit', name: 'app_streamer_profile_edit')]
    public function edit(Request $request,UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, StreamerAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
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
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/streamer/search', name: 'app_streamer_search')]
    public function search(CompanyRepository $repository): Response
    {
        $companies = $repository->findAll();
        return $this->render('search_page/search_page.html.twig', [
            'companies' => $companies
        ]);
    }
    #[Route('/streamer/search/profile/{id}', name: 'app_show_company')]
    public function show_profile(Company $company): Response
    {
        return $this->render('search_page/show_profile.html.twig', [
            'company' => $company
        ]);
    }
}
