<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Proposal;
use App\Entity\ProposalStatus;
use App\Form\ProposalType;
use App\Repository\ProposalRepository;
use App\Repository\ProposalStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProposalController extends AbstractController
{
    #[Route('/streamer/offers', name: 'app_streamer_offers')]
    public function offers(ProposalRepository $proposalRepo): Response
    {
        $proposals = $proposalRepo->findBy(['streamer' => $this->getUser()]);
        foreach ($proposals as $proposal) {
            $proposal->getCompany()->getSiret();
            $proposal->getHasProposalStatus()->getName();
        }
        return $this->render('offers/offers.html.twig', [
            'proposals' => $proposals,
        ]);
    }
    #[Route('/company/requests', name: 'app_company_requests')]
    public function requests(ProposalRepository $proposalRepo): Response
    {
        $proposals = $proposalRepo->findBy(['company' => $this->getUser()]);
        foreach ($proposals as $proposal) {
            $proposal->getCompany()->getSiret();
            $proposal->getHasProposalStatus()->getName();
        }
        return $this->render('offers/offers.html.twig', [
            'proposals' => $proposals,
        ]);
    }

    #[Route('/streamer/offers/{id}/accept', name: 'app_streamer_offer_accept')]
    public function accept(ProposalRepository $proposalRepo, ProposalStatusRepository $proposalStatusRepo, $id,
        EntityManagerInterface
    $entityManager): Response
    {
        $proposal = $proposalRepo->find($id);
        $proposal->setHasProposalStatus($proposalStatusRepo->find(2));
        $entityManager->persist($proposal);
        $entityManager->flush();
        return $this->redirectToRoute('app_streamer_offers');
    }

    #[Route('/streamer/offers/{id}/reject', name: 'app_streamer_offer_reject')]
    public function reject(ProposalRepository $proposalRepo, ProposalStatusRepository $proposalStatusRepo, $id,
        EntityManagerInterface
    $entityManager): Response
    {
        $proposal = $proposalRepo->find($id);
        $proposal->setHasProposalStatus($proposalStatusRepo->find(3));
        $entityManager->persist($proposal);
        $entityManager->flush();
        return $this->redirectToRoute('app_streamer_offers');
    }

    #[Route('/company/search/profile/{id}/proposal', name: 'app_company_make_proposal')]
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
