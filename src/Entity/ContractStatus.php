<?php

namespace App\Entity;

use App\Repository\ContractStatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractStatusRepository::class)]
class ContractStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'hasContractStatus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contract $contract = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signature_company = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signature_streamer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getSignatureCompany(): ?string
    {
        return $this->signature_company;
    }

    public function setSignatureCompany(?string $signature_company): self
    {
        $this->signature_company = $signature_company;

        return $this;
    }

    public function getSignatureStreamer(): ?string
    {
        return $this->signature_streamer;
    }

    public function setSignatureStreamer(?string $signature_streamer): self
    {
        $this->signature_streamer = $signature_streamer;

        return $this;
    }
}
