<?php

namespace App\Entity;

use App\Repository\StreamerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: StreamerRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class Streamer implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $followers = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $id_streamer = null;

    #[ORM\Column]
    private ?bool $isMature = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'streamers')]
    private Collection $streamThis;

    #[ORM\OneToMany(mappedBy: 'streamer', targetEntity: Contract::class)]
    private Collection $streamerContract;

    #[ORM\ManyToMany(targetEntity: Company::class, inversedBy: 'streamers')]
    private Collection $isBlacklisted;

    #[ORM\OneToMany(mappedBy: 'streamer', targetEntity: Proposal::class)]
    private Collection $hasProposal;

    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'streamers')]
    private Collection $isOn;

    #[ORM\ManyToMany(targetEntity: TypeOfContent::class, inversedBy: 'streamers')]
    private Collection $isContent;

    #[ORM\Column(length: 255)]
    private ?string $profile_picture = null;

    #[ORM\OneToMany(mappedBy: 'hasRepresentativeStreamer', targetEntity: Representative::class)]
    private Collection $representatives;

    public function __construct()
    {
        $this->streamThis = new ArrayCollection();
        $this->streamerContract = new ArrayCollection();
        $this->isBlacklisted = new ArrayCollection();
        $this->hasProposal = new ArrayCollection();
        $this->isOn = new ArrayCollection();
        $this->isContent = new ArrayCollection();
        $this->representatives = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_STREAMER';

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

    public function getFollowers(): ?string
    {
        return $this->followers;
    }

    public function setFollowers(string $followers): self
    {
        $this->followers = $followers;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getIdStreamer(): ?string
    {
        return $this->id_streamer;
    }

    public function setIdStreamer(string $id_streamer): self
    {
        $this->id_streamer = $id_streamer;

        return $this;
    }

    public function isIsMature(): ?bool
    {
        return $this->isMature;
    }

    public function setIsMature(bool $isMature): self
    {
        $this->isMature = $isMature;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getStreamThis(): Collection
    {
        return $this->streamThis;
    }

    public function addStreamThis(Category $streamThis): self
    {
        if (!$this->streamThis->contains($streamThis)) {
            $this->streamThis->add($streamThis);
        }

        return $this;
    }

    public function removeStreamThis(Category $streamThis): self
    {
        $this->streamThis->removeElement($streamThis);

        return $this;
    }

    /**
     * @return Collection<int, Contract>
     */
    public function getStreamerContract(): Collection
    {
        return $this->streamerContract;
    }

    public function addStreamerContract(Contract $streamerContract): self
    {
        if (!$this->streamerContract->contains($streamerContract)) {
            $this->streamerContract->add($streamerContract);
            $streamerContract->setStreamer($this);
        }

        return $this;
    }

    public function removeStreamerContract(Contract $streamerContract): self
    {
        if ($this->streamerContract->removeElement($streamerContract)) {
            // set the owning side to null (unless already changed)
            if ($streamerContract->getStreamer() === $this) {
                $streamerContract->setStreamer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getIsBlacklisted(): Collection
    {
        return $this->isBlacklisted;
    }

    public function addIsBlacklisted(Company $isBlacklisted): self
    {
        if (!$this->isBlacklisted->contains($isBlacklisted)) {
            $this->isBlacklisted->add($isBlacklisted);
        }

        return $this;
    }

    public function removeIsBlacklisted(Company $isBlacklisted): self
    {
        $this->isBlacklisted->removeElement($isBlacklisted);

        return $this;
    }

    /**
     * @return Collection<int, Proposal>
     */
    public function getHasProposal(): Collection
    {
        return $this->hasProposal;
    }

    public function addHasProposal(Proposal $hasProposal): self
    {
        if (!$this->hasProposal->contains($hasProposal)) {
            $this->hasProposal->add($hasProposal);
            $hasProposal->setStreamer($this);
        }

        return $this;
    }

    public function removeHasProposal(Proposal $hasProposal): self
    {
        if ($this->hasProposal->removeElement($hasProposal)) {
            // set the owning side to null (unless already changed)
            if ($hasProposal->getStreamer() === $this) {
                $hasProposal->setStreamer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Platform>
     */
    public function getIsOn(): Collection
    {
        return $this->isOn;
    }

    public function addIsOn(Platform $isOn): self
    {
        if (!$this->isOn->contains($isOn)) {
            $this->isOn->add($isOn);
        }

        return $this;
    }

    public function removeIsOn(Platform $isOn): self
    {
        $this->isOn->removeElement($isOn);

        return $this;
    }

    /**
     * @return Collection<int, TypeOfContent>
     */
    public function getIsContent(): Collection
    {
        return $this->isContent;
    }

    public function addIsContent(TypeOfContent $isContent): self
    {
        if (!$this->isContent->contains($isContent)) {
            $this->isContent->add($isContent);
        }

        return $this;
    }

    public function removeIsContent(TypeOfContent $isContent): self
    {
        $this->isContent->removeElement($isContent);

        return $this;
    }


    public function getProfilePicture(): ?string
    {
        return $this->profile_picture;
    }

    public function setProfilePicture(string $profile_picture): self
    {
        $this->profile_picture = $profile_picture;

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
            $representative->setHasRepresentativeStreamer($this);
        }

        return $this;
    }

    public function removeRepresentative(Representative $representative): self
    {
        if ($this->representatives->removeElement($representative)) {
            // set the owning side to null (unless already changed)
            if ($representative->getHasRepresentativeStreamer() === $this) {
                $representative->setHasRepresentativeStreamer(null);
            }
        }

        return $this;
    }

}