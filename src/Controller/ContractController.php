<?php

namespace App\Controller;
use App\Entity\Contract;
use App\Entity\ContractStatus;
use App\Entity\Representative;
use App\Entity\Streamer;
use App\Form\ContractType;
use App\Form\RepresentativeType;
use App\Repository\ContractsRepository;
use App\Repository\ContractStatusRepository;
use App\Repository\ProposalRepository;
use App\Repository\StreamerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContractController extends AbstractController
{
    #[Route('/company/contracts', name: 'app_company_contracts')]
    public function contracts_company(ContractsRepository $contractsRepo): Response
    {
        $contracts = $contractsRepo->findBy(['company' => $this->getUser()]);
        return $this->render('contract/contract.html.twig', [
            'contracts' => $contracts,
        ]);
    }


    #[Route('/company/contracts/{id}', name: 'app_company_show_contract')]
    public function show_contract(Contract $contract, StreamerRepository $streamerRepo, ContractStatusRepository $contractStatusRepo): Response
    {
        $contractStatus = $contractStatusRepo->findBy(['contract' => $contract]);
        $contract->setStreamer($streamerRepo->findOneBy(['id' => $contract->getStreamer()]));
        $html =  $this->renderView('pdf_generator/index.html.twig', [
            'contract' => $contract,
            'contractStatus' => $contractStatus,
        ]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }



    #[Route('/streamer/contracts', name: 'app_streamer_contracts')]
    public function index_streamer(ContractsRepository $contractsRepo): Response
    {
        $contracts = $contractsRepo->findBy(['streamer' => $this->getUser()]);
        return $this->render('contract/contract.html.twig', [
            'contracts' => $contracts,
        ]);
    }

    #[Route('/company/requests/make/contract/{id}', name: 'app_make_contract')]
    public function make_contract(Streamer $streamer, Request $request, EntityManagerInterface $entityManager): Response
    {
        $contract = new Contract();
        $status = new ContractStatus();
        $company = $this->getUser();
        $status->setContract($contract);
        $status->setName('En attente de signature');
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contract->setStreamer($streamer);
            $contract->setCompany($company);
            $contract->setStartDate(new \DateTime('now'));
            $entityManager->persist($contract);
            $entityManager->persist($status);
            $entityManager->flush();
            return $this->redirectToRoute('app_company_contracts');
        }

        return $this->render('contract/make_contract.html.twig', [
            'contract_form' => $form->createView(),
        ]);
    }

    #[Route('/company/contracts/{id}/signature', name: 'app_company_contract_signature')]
    public function signature(ContractStatusRepository $contractStatusRepo, Request $request, EntityManagerInterface $entityManager, Contract $contract): Response
    {
        // new representative
        $representing = new Representative();
        // get current user
        $company = $this->getUser();
        // get contract status
        $contractStatus = $contractStatusRepo->findOneBy(['contract' => $contract]);
        // create form representative
        $form = $this->createForm(RepresentativeType::class, $representing);
        // request form
        $form->handleRequest($request);
        // if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // set company to representative
            $representing->setHasRepresentative($company);
            $sign = $form->get('first_name')->getData() . ' ' . $form->get('last_name')->getData();
            $contractStatus->setSignatureCompany($sign);
            $representing->setRole('ROLE_COMPANY');
            // check if contract is signed by company and streamer
            if ($contractStatus->getSignatureStreamer() !== null and $contractStatus->getSignatureCompany() !== null) {
                // set contract status to signed
                $contractStatus->setName('Contrat signé');
                // save contract status
                $entityManager->persist($contractStatus);
            }
            $entityManager->persist($representing);
            $entityManager->persist($contractStatus);
            $entityManager->flush();
            return $this->redirectToRoute('app_company_contracts');
        }

        return $this->render('representative/signature.html.twig', [
            'representative_form' => $form->createView(),
        ]);
    }

	#[Route('/company/requests/{id}/delete', name: 'app_delete_offers')]
	public function delete_offers($id, ProposalRepository $proposal_repo): Response
	{
			$proposal = $proposal_repo->find($id);

			$proposal_repo->remove($proposal, true);


		return $this->redirectToRoute('app_company_requests');

	}



}
