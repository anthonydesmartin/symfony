<?php

namespace App\Entity;

use App\Repository\TypeOfContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeOfContentRepository::class)]
class TypeOfContent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Streamer::class, mappedBy: 'isContent')]
    private Collection $streamers;

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
            $streamer->addIsContent($this);
        }

        return $this;
    }

    public function removeStreamer(Streamer $streamer): self
    {
        if ($this->streamers->removeElement($streamer)) {
            $streamer->removeIsContent($this);
        }

        return $this;
    }
}
