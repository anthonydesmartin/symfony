<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Contract;
use App\Entity\ContractStatus;
use App\Entity\Streamer;
use App\Form\ContractType;
use App\Repository\ContractsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContractController extends AbstractController
{
    #[Route('/company/contracts', name: 'app_company_contracts')]
    public function index_company(ContractsRepository $contractsRepo): Response
    {
        $contracts = $contractsRepo->findBy(['company' => $this->getUser()]);
        return $this->render('contract/contract.html.twig', [
            'contracts' => $contracts,
        ]);
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
        $status->setName('En attente de validation');
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


}
