<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Streamer;
use App\Form\RegistrationCompanyFormType;
use App\Form\RegistrationFormType;
use App\Security\StreamerAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{

    function clearHeaders(){
        header_remove();
		session_id('streamer');
    }

    function getStreamerTwitchIdAndPp($id, $client)
    {
        $this->clearHeaders();
        $twitch_oauth_token = 'Bearer trmxm96ywdu3wfb820qag5i973g8mp';
        $twitch_client_id = 'niurwn3bhzyl581c7s56w9y5l9i2zl';
        $response = $client->request(
            'GET',
            'https://api.twitch.tv/helix/users?id='.$id,
            [
                'headers' => [
                    "Authorization: $twitch_oauth_token",
                    "Client-Id: $twitch_client_id",
                    "ContentType: application/json",
                ],
            ]
        );

        return $response->toArray()['data'][0];
    }

    function getStreamerTwitchFollowers($client, $id)
    {
        $this->clearHeaders();
        $twitch_oauth_token = 'Bearer trmxm96ywdu3wfb820qag5i973g8mp';
        $twitch_client_id = 'niurwn3bhzyl581c7s56w9y5l9i2zl';
        $response = $client->request(
            'GET',
            'https://api.twitch.tv/helix/users/follows?to_id='.$id,
            [
                'headers' => [
                    "Authorization: $twitch_oauth_token",
                    "Client-Id: $twitch_client_id",
                    "ContentType: application/json",
                ],
            ]
        );

        return $response->toArray()['total'];
    }

    #[Route('/register/streamer', name: 'app_register_streamer')]
    public function registerStreamer(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        StreamerAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        HttpClientInterface $client
    ): Response {
        $user = new Streamer();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // retrieve twitch id and profile picture
            $streamer = $this->getStreamerTwitchIdAndPp($form->get('username')->getData(), $client);
            // set streamer twitch id
            $user->setIdStreamer($streamer['id']);
            // get streamer followers
            $followers = $this->getStreamerTwitchFollowers($client, $streamer['id']);
            // set streamer followers
            $user->setFollowers($followers);
            // get streamer profile picture
            $pp = $streamer['profile_image_url'];
            // set streamer profile picture
            $user->setProfilePicture($pp);
            // encode password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            // set user role
            $user->setRoles(['ROLE_STREAMER']);
            // save user in database
            $entityManager->persist($user);

            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'title' => 'Créer un compte',
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/company', name: 'app_register_company')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        StreamerAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new Company();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles(['ROLE_COMPANY']);


            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'title' => 'Créer un compte',
            'registrationForm' => $form->createView(),
        ]);
    }
}