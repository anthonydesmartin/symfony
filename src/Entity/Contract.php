<?php

namespace App\Entity;

use App\Repository\ContractsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractsRepository::class)]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $format = null;

    #[ORM\Column(length: 255)]
    private ?string $modalities = null;

    #[ORM\ManyToOne(inversedBy: 'streamerContract')]
    private ?Streamer $streamer = null;

    #[ORM\ManyToOne(inversedBy: 'contracts')]
    private ?Company $compagnyContract = null;

    #[ORM\OneToMany(mappedBy: 'contract', targetEntity: ContractStatus::class)]
    private Collection $hasContractStatus;

    public function __construct()
    {
        $this->hasContractStatus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
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

    public function getModalities(): ?string
    {
        return $this->modalities;
    }

    public function setModalities(string $modalities): self
    {
        $this->modalities = $modalities;

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

    public function getCompagnyContract(): ?Company
    {
        return $this->compagnyContract;
    }

    public function setCompagnyContract(?Company $compagnyContract): self
    {
        $this->compagnyContract = $compagnyContract;

        return $this;
    }

    /**
     * @return Collection<int, ContractStatus>
     */
    public function getHasContractStatus(): Collection
    {
        return $this->hasContractStatus;
    }

    public function addHasContractStatus(ContractStatus $hasContractStatus): self
    {
        if (!$this->hasContractStatus->contains($hasContractStatus)) {
            $this->hasContractStatus->add($hasContractStatus);
            $hasContractStatus->setContract($this);
        }

        return $this;
    }

    public function removeHasContractStatus(ContractStatus $hasContractStatus): self
    {
        if ($this->hasContractStatus->removeElement($hasContractStatus)) {
            // set the owning side to null (unless already changed)
            if ($hasContractStatus->getContract() === $this) {
                $hasContractStatus->setContract(null);
            }
        }

        return $this;
    }
}
