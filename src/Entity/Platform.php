<?php

namespace App\Entity;

use App\Repository\PlatformsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatformsRepository::class)]
class Platform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Streamer::class, mappedBy: 'isOn')]
    private Collection $streamers;

    #[ORM\ManyToOne(inversedBy: 'platforms')]
    private ?PlatformType $hasType = null;

    public function __construct()
    {
        $this->streamers = new ArrayCollection();
    }

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
            $streamer->addIsOn($this);
        }

        return $this;
    }

    public function removeStreamer(Streamer $streamer): self
    {
        if ($this->streamers->removeElement($streamer)) {
            $streamer->removeIsOn($this);
        }

        return $this;
    }

    public function getHasType(): ?PlatformType
    {
        return $this->hasType;
    }

    public function setHasType(?PlatformType $hasType): self
    {
        $this->hasType = $hasType;

        return $this;
    }
}
