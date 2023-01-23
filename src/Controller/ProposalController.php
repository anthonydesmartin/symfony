<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Proposal;
use App\Entity\ProposalStatus;
use App\Entity\Streamer;
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

    #[Route('/company/search/profile/{id}/proposal', name: 'make_proposal')]
    public function make_proposal(Streamer $streamer, Request $request, EntityManagerInterface $entityManager, ProposalStatusRepository $proposalStatusRepo): Response
    {
        $company = $this->getUser();
        $proposal = new Proposal();
        $proposal->setStreamer($streamer);
        $proposal->setCompany($company);
        $form = $this->createForm(ProposalType::class, $proposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $proposal->setStreamer($streamer)->setCompany($company);
            $proposal->setHasProposalStatus($proposalStatusRepo->find(1));
            $proposal->setDescription($form->get('description')->getData());
            $proposal->setFormat($form->get('format')->getData());
            $entityManager->persist($proposal);
            $entityManager->flush();
            return $this->redirectToRoute('app_company_requests');
        }
        return $this->render('offers/make_offers.html.twig', [
            'proposalForm' => $form->createView(),
        ]);
    }
}
