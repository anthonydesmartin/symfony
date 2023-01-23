<?php

namespace App\Controller;

use App\Entity\Company;
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

    #[Route('/company/requests/make/contract', name: 'app_make_contract')]
    public function make_contract(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contract = new Contract();
        $status = new ContractStatus();
        $company = $this->getUser();
        $status->setContract($contract);
        $status->setName('En attente');
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contract->setStreamer($this->getUser());
            $contract->setCompany($company);
            $contract->setStartDate(new \DateTime('now'));

            $entityManager->persist($contract);
            $entityManager->persist($status);
            $entityManager->flush();

            return $this->redirectToRoute('app_streamer_contract');
        }

        return $this->render('contract/make_contract.html.twig', [
            'contract_form' => $form->createView(),
            'company' => $company
        ]);
        return $this->render('contract/make_contract.html.twig', [
            'contracts' => $contracts,
        ]);
    }


}
