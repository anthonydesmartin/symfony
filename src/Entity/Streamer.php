<?php

namespace App\Entity;

use App\Repository\StreamersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StreamersRepository::class)]
class Streamer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column]
    private ?int $followers = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 14, nullable: true)]
    private ?bigint $siret = null;

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
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'Streamer', targetEntity: Relation::class)]
    private Collection $relations;

    #[ORM\OneToMany(mappedBy: 'Streamer', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\Column]
    private ?bool $IsMature = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $streamerId = null;

    public function __construct()
    {
        $this->streamThis = new ArrayCollection();
        $this->streamerContract = new ArrayCollection();
        $this->isBlacklisted = new ArrayCollection();
        $this->hasProposal = new ArrayCollection();
        $this->isOn = new ArrayCollection();
        $this->isContent = new ArrayCollection();
        $this->relations = new ArrayCollection();
        $this->messages = new ArrayCollection();
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

    public function getFollowers(): ?int
    {
        return $this->followers;
    }

    public function setFollowers(int $followers): self
    {
        $this->followers = $followers;

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

    public function getSiret(): ?int
    {
        return $this->siret;
    }

    public function setSiret(?int $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getStreamThis(): Collection
    {
        return $this->streamThis;
    }

    public function addStreamThi(Category $streamThi): self
    {
        if (!$this->streamThis->contains($streamThi)) {
            $this->streamThis->add($streamThi);
        }

        return $this;
    }

    public function removeStreamThi(Category $streamThi): self
    {
        $this->streamThis->removeElement($streamThi);

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
            $relation->setStreamer($this);
        }

        return $this;
    }

    public function removeRelation(Relation $relation): self
    {
        if ($this->relations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getStreamer() === $this) {
                $relation->setStreamer(null);
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
            $message->setStreamer($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getStreamer() === $this) {
                $message->setStreamer(null);
            }
        }

        return $this;
    }

    public function isIsMature(): ?bool
    {
        return $this->IsMature;
    }

    public function setIsMature(bool $IsMature): self
    {
        $this->IsMature = $IsMature;

        return $this;
    }

    public function getStreamerId(): ?string
    {
        return $this->streamerId;
    }

    public function setStreamerId(string $streamerId): self
    {
        $this->streamerId = $streamerId;

        return $this;
    }
}
