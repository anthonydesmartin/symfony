<?php

namespace App\Controller;
use App\Entity\Contract;
use App\Entity\ContractStatus;
use App\Entity\Representative;
use App\Entity\Streamer;
use App\Form\ContractType;
use App\Form\RepresentativeType;
use App\Repository\CompanyRepository;
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
        // Get all contracts related to the company
        $contracts = $contractsRepo->findBy(['company' => $this->getUser()]);
        return $this->render('contract/contract.html.twig', [
            'contracts' => $contracts,
        ]);
    }

    #[Route('/streamer/contracts', name: 'app_streamer_contracts')]
    public function contracts_streamer(ContractsRepository $contractsRepo): Response
    {
        // Get all contracts related to the company
        $contracts = $contractsRepo->findBy(['streamer' => $this->getUser()]);
        return $this->render('contract/contract.html.twig', [
            'contracts' => $contracts,
        ]);
    }


    #[Route('/company/contracts/{id}', name: 'app_company_show_contract')]
    public function show_contract(Contract $contract, StreamerRepository $streamerRepo, ContractStatusRepository $contractStatusRepo): Response
    {
        // Get the contract status
        $contractStatus = $contractStatusRepo->findBy(['contract' => $contract]);
        // set the streamer to the contract
        $contract->setStreamer($streamerRepo->findOneBy(['id' => $contract->getStreamer()]));
        // prepare twig with variable for pdf
        $html =  $this->renderView('pdf_generator/index.html.twig', [
            'contract' => $contract,
            'contractStatus' => $contractStatus,
        ]);
        // create pdf
        $dompdf = new Dompdf();
        // load html
        $dompdf->loadHtml($html);
        // render pdf
        $dompdf->render();
        // output pdf
        return new Response (
            $dompdf->stream('Contrat '.$contract->getStreamer()->getUsername().'-'.$contract->getCompany()->getName(), ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }

    #[Route('/streamer/contracts/{id}', name: 'app_streamer_show_contract')]
    public function show_contract_streamer(Contract $contract, StreamerRepository $streamerRepo, ContractStatusRepository $contractStatusRepo): Response
    {
        // Get the contract status
        $contractStatus = $contractStatusRepo->findBy(['contract' => $contract]);
        // set the streamer to the contractz
        $contract->setStreamer($streamerRepo->findOneBy(['id' => $this->getUser()]));
        // prepare twig with variable for pdf
        $html =  $this->renderView('pdf_generator/index.html.twig', [
            'contract' => $contract,
            'contractStatus' => $contractStatus,
        ]);
        // create pdf
        $dompdf = new Dompdf();
        // load html
        $dompdf->loadHtml($html);
        // render pdf
        $dompdf->render();
        // output pdf
        return new Response (
            $dompdf->stream('Contrat '.$contract->getStreamer()->getUsername().'-'.$contract->getCompany()->getName(), ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }



    #[Route('/streamer/contracts', name: 'app_streamer_contracts')]
    public function index_streamer(ContractsRepository $contractsRepo): Response
    {
        // Get all contracts related to the company
        $contracts = $contractsRepo->findBy(['streamer' => $this->getUser()]);
        // render the view
        return $this->render('contract/contract.html.twig', [
            'contracts' => $contracts,
        ]);
    }

    #[Route('/company/requests/make/contract/{id}', name: 'app_make_contract')]
    public function make_contract(Streamer $streamer, Request $request, EntityManagerInterface $entityManager,ProposalRepository $proposalRepo): Response
    {
        // Create a new contract
        $contract = new Contract();
        // Create a new contract status
        $status = new ContractStatus();
        // get proposal link to the contract
        $proposal = $proposalRepo->findOneBy(['streamer' => $streamer]);
        // get current user
        $company = $this->getUser();
        // set contract to contract status
        $status->setContract($contract);
        // set contract status to "En attente de signature"
        $status->setName('En attente de signature');
        // create form contract
        $form = $this->createForm(ContractType::class, $contract);
        // handle request
        $form->handleRequest($request);
        // if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // remove the proposal
            $entityManager->remove($proposal);
            // set streamer to contract
            $contract->setStreamer($streamer);
            // set company to contract
            $contract->setCompany($company);
            // set start date to today
            $contract->setStartDate(new \DateTime('now'));
            //save contract
            $entityManager->persist($contract);
            // save contract status
            $entityManager->persist($status);
            // flush
            $entityManager->flush();
            // redirect to company contracts
            return $this->redirectToRoute('app_company_contracts');
        }

        //render form
        return $this->render('contract/make_contract.html.twig', [
            'contract_form' => $form->createView(),
        ]);
    }

    #[Route('/company/contracts/{id}/signature', name: 'app_company_contract_signature')]
    public function signature_company(ContractStatusRepository $contractStatusRepo, Request $request, EntityManagerInterface $entityManager, Contract $contract): Response
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
            // get form data and concat to string for signature
            $sign = $form->get('first_name')->getData() . ' ' . $form->get('last_name')->getData();
            // set signature to contract status
            $contractStatus->setSignatureCompany($sign);
            // set representative role to "company"
            $representing->setRole('ROLE_COMPANY');
            // check if contract is signed by company and streamer
            if ($contractStatus->getSignatureStreamer() !== null and $contractStatus->getSignatureCompany() !== null) {
                // set contract status to signed
                $contractStatus->setName('Contrat signé');
                // save contract status
                $entityManager->persist($contractStatus);
            }
            // save representative
            $entityManager->persist($representing);
            // save contract status
            $entityManager->persist($contractStatus);
            // flush
            $entityManager->flush();
            // redirect to company contracts
            return $this->redirectToRoute('app_company_contracts');
        }
        // render form
        return $this->render('representative/signature.html.twig', [
            'representative_form' => $form->createView(),
        ]);
    }

    #[Route('/streamer/contracts/{id}/signature', name: 'app_streamer_contract_signature')]
    public function signature_streamer(ContractStatusRepository $contractStatusRepo, Request $request, EntityManagerInterface $entityManager, Contract $contract): Response
    {
        // new representative
        $representing = new Representative();
        // get current user
        $streamer = $this->getUser();
        // get contract status
        $contractStatus = $contractStatusRepo->findOneBy(['contract' => $contract]);
        // create form representative
        $form = $this->createForm(RepresentativeType::class, $representing);
        // request form
        $form->handleRequest($request);
        // if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // set company to representative
            $representing->setHasRepresentativeStreamer($streamer);
            // get form data and concat to string for signature
            $sign = $form->get('first_name')->getData() . ' ' . $form->get('last_name')->getData();
            // set signature to contract status
            $contractStatus->setSignatureCompany($sign);
            // set representative role to "company"
            $representing->setRole('ROLE_STREAMER');
            // check if contract is signed by company and streamer
            if ($contractStatus->getSignatureStreamer() !== null and $contractStatus->getSignatureCompany() !== null) {
                // set contract status to signed
                $contractStatus->setName('Contrat signé');
                // save contract status
                $entityManager->persist($contractStatus);
            }
            // save representative
            $entityManager->persist($representing);
            // save contract status
            $entityManager->persist($contractStatus);
            // flush
            $entityManager->flush();
            // redirect to company contracts
            return $this->redirectToRoute('app_streamer_contracts');
        }
        // render form
        return $this->render('representative/signature.html.twig', [
            'representative_form' => $form->createView(),
        ]);
    }


}
