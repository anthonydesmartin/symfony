<?php

namespace App\Entity;

use App\Repository\ProposalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProposalRepository::class)]
class Proposal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $format = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'hasProposal')]
    private ?Streamer $streamer = null;

    #[ORM\ManyToOne(inversedBy: 'makeProposal')]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'proposals')]
    private ?ProposalStatus $hasProposalStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStreamer(): ?Streamer
    {
        return $this->streamer;
    }

    public function setStreamer(?Streamer $streamer): self
    {
        $this->streamer = $streamer;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getHasProposalStatus(): ?ProposalStatus
    {
        return $this->hasProposalStatus;
    }

    public function setHasProposalStatus(?ProposalStatus $hasProposalStatus): self
    {
        $this->hasProposalStatus = $hasProposalStatus;

        return $this;
    }
}
