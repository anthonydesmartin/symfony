<?php

namespace App\Entity;

use App\Repository\RepresentativeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepresentativeRepository::class)]
class Representative
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\ManyToOne(inversedBy: 'representatives')]
    private ?Company $hasRepresentative = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getHasRepresentative(): ?Company
    {
        return $this->hasRepresentative;
    }

    public function setHasRepresentative(?Company $hasRepresentative): self
    {
        $this->hasRepresentative = $hasRepresentative;

        return $this;
    }
}
