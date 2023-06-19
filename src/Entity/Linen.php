<?php

namespace App\Entity;

use App\Repository\LinenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinenRepository::class)]
class Linen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $units = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified = null;

    #[ORM\OneToMany(mappedBy: 'Linen', targetEntity: CleanLinen::class)]
    private Collection $cleanLinens;

    public function __construct()
    {
        $this->cleanLinens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUnits(): ?int
    {
        return $this->units;
    }

    public function setUnits(?int $units): static
    {
        $this->units = $units;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(?\DateTimeInterface $modified): static
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @return Collection<int, CleanLinen>
     */
    public function getCleanLinens(): Collection
    {
        return $this->cleanLinens;
    }

    public function addCleanLinen(CleanLinen $cleanLinen): static
    {
        if (!$this->cleanLinens->contains($cleanLinen)) {
            $this->cleanLinens->add($cleanLinen);
            $cleanLinen->setLinen($this);
        }

        return $this;
    }

    public function removeCleanLinen(CleanLinen $cleanLinen): static
    {
        if ($this->cleanLinens->removeElement($cleanLinen)) {
            // set the owning side to null (unless already changed)
            if ($cleanLinen->getLinen() === $this) {
                $cleanLinen->setLinen(null);
            }
        }

        return $this;
    }
}
