<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $siret = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $head_office = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $register = null;

    #[ORM\OneToMany(mappedBy: 'compagnyContract', targetEntity: Contract::class)]
    private Collection $contracts;

    #[ORM\ManyToMany(targetEntity: Streamer::class, mappedBy: 'isBlacklisted')]
    private Collection $streamers;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Proposal::class)]
    private Collection $makeProposal;

    #[ORM\OneToMany(mappedBy: 'hasRepresentative', targetEntity: Representative::class)]
    private Collection $representatives;

    #[ORM\OneToMany(mappedBy: 'Company', targetEntity: Relation::class)]
    private Collection $relations;

    #[ORM\OneToMany(mappedBy: 'Company', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->contracts = new ArrayCollection();
        $this->streamers = new ArrayCollection();
        $this->makeProposal = new ArrayCollection();
        $this->representatives = new ArrayCollection();
        $this->relations = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->siret;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_COMPANY';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getHeadOffice(): ?string
    {
        return $this->head_office;
    }

    public function setHeadOffice(?string $head_office): self
    {
        $this->head_office = $head_office;

        return $this;
    }

    public function getRegister(): ?string
    {
        return $this->register;
    }

    public function setRegister(?string $register): self
    {
        $this->register = $register;

        return $this;
    }

    /**
     * @return Collection<int, Contract>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): self
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setCompagnyContract($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): self
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getCompagnyContract() === $this) {
                $contract->setCompagnyContract(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Streamer>
     */
    public function getStreamers(): Collection
    {
        return $this->streamers;
    }

    public function addStreamer(Streamer $streamer): self
    {
        if (!$this->streamers->contains($streamer)) {
            $this->streamers->add($streamer);
            $streamer->addIsBlacklisted($this);
        }

        return $this;
    }

    public function removeStreamer(Streamer $streamer): self
    {
        if ($this->streamers->removeElement($streamer)) {
            $streamer->removeIsBlacklisted($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Proposal>
     */
    public function getMakeProposal(): Collection
    {
        return $this->makeProposal;
    }

    public function addMakeProposal(Proposal $makeProposal): self
    {
        if (!$this->makeProposal->contains($makeProposal)) {
            $this->makeProposal->add($makeProposal);
            $makeProposal->setCompany($this);
        }

        return $this;
    }

    public function removeMakeProposal(Proposal $makeProposal): self
    {
        if ($this->makeProposal->removeElement($makeProposal)) {
            // set the owning side to null (unless already changed)
            if ($makeProposal->getCompany() === $this) {
                $makeProposal->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Representative>
     */
    public function getRepresentatives(): Collection
    {
        return $this->representatives;
    }

    public function addRepresentative(Representative $representative): self
    {
        if (!$this->representatives->contains($representative)) {
            $this->representatives->add($representative);
            $representative->setHasRepresentative($this);
        }

        return $this;
    }

    public function removeRepresentative(Representative $representative): self
    {
        if ($this->representatives->removeElement($representative)) {
            // set the owning side to null (unless already changed)
            if ($representative->getHasRepresentative() === $this) {
                $representative->setHasRepresentative(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Relation>
     */
    public function getRelations(): Collection
    {
        return $this->relations;
    }

    public function addRelation(Relation $relation): self
    {
        if (!$this->relations->contains($relation)) {
            $this->relations->add($relation);
            $relation->setCompany($this);
        }

        return $this;
    }

    public function removeRelation(Relation $relation): self
    {
        if ($this->relations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getCompany() === $this) {
                $relation->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setCompany($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getCompany() === $this) {
                $message->setCompany(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
