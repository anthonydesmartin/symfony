<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Contract;
use App\Entity\ContractStatus;
use App\Entity\Proposal;
use App\Entity\ProposalStatus;
use App\Entity\Streamer;
use App\Form\ContractType;
use App\Form\ProposalType;
use App\Form\RegistrationFormType;
use App\Controller\RegistrationController;
use App\Repository\CompanyRepository;
use App\Repository\ContractsRepository;
use App\Repository\ProposalRepository;
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
        $pp = $this->getUser()->getProfilePicture();
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
            'missing_info' => $missing_info,
            'pp' => $pp,
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
            'edit_title' => 'Modifier mon profil',
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
            'missing_info_company' => $missing_info_company,
            'missing_info_streamer' => $missing_info_streamer
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
            'contracts' => $contracts
        ]);
    }

    #[Route('/streamer/offers', name: 'app_streamer_show_proposal')]
    public function show_proposal(ProposalRepository $proposalRepo): Response
    {
        $streamer = $this->getUser();
        $proposals = $proposalRepo->findBy(['streamer' => $streamer]);
        return $this->render('offers/offers.html.twig', [
            'proposals' => $proposals
        ]);
    }
    #[Route('/streamer/search/profile/{id}/proposal', name: 'app_streamer_make_proposal')]
    public function make_proposal(Company $company, Request $request, EntityManagerInterface $entityManager): Response
    {
        $streamer = $this->getUser();
        $proposal = new Proposal();
        $proposal->setStreamer($streamer);
        $proposal->setCompany($company);
        $form = $this->createForm(ProposalType::class, $proposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $proposal_status = new ProposalStatus();
            $proposal->setStreamer($streamer)->setCompany($company);
            $proposal_status->addProposal($proposal);
            $proposal->setHasProposalStatus($proposal_status);
            $proposal_status->setName('En attente de validation');
            $proposal->setDescription($form->get('description')->getData());
            $proposal->setFormat($form->get('format')->getData());
            $entityManager->persist($proposal);
            $entityManager->persist($proposal_status);
            $entityManager->flush();
            return $this->redirectToRoute('app_streamer_show_proposal');
        }
        return $this->render('offers/make_offers.html.twig', [
            'proposalForm' => $form->createView(),
        ]);
    }

//    #[Route('/streamer/search/profile/{id}/make/relation', name: 'app_streamer_make_relation')]
//    public function make_contract(Company $company, Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $contract = new Contract();
//        $status = new ContractStatus();
//        $status->setContract($contract);
//        $status->setName('En attente');
//        $form = $this->createForm(ContractType::class, $contract);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $contract->setStreamer($this->getUser());
//            $contract->setCompany($company);
//            $contract->setStartDate(new \DateTime('now'));
//
//            $entityManager->persist($contract);
//            $entityManager->persist($status);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_streamer_contract');
//        }
//
//        return $this->render('contract/make_contract.html.twig', [
//            'contract_form' => $form->createView(),
//            'company' => $company
//        ]);
//    }

}
