<?php

namespace App\Entity;

use App\Repository\CleanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CleanRepository::class)]
class Clean
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cleans')]
    private ?Property $property = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $scheduled = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified = null;

    #[ORM\OneToMany(mappedBy: 'clean', targetEntity: CleanPhoto::class)]
    private Collection $cleanPhotos;

    #[ORM\OneToMany(mappedBy: 'clean', targetEntity: CleanSupply::class)]
    private Collection $cleanSupplies;

    #[ORM\OneToMany(mappedBy: 'clean', targetEntity: CleanLinen::class)]
    private Collection $cleanLinens;

    #[ORM\OneToMany(mappedBy: 'clean', targetEntity: CleanHousekeeper::class)]
    private Collection $cleanHousekeepers;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $inspectionNotes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $suppliesClaimed = null;

    public function __construct()
    {
        $this->cleanPhotos = new ArrayCollection();
        $this->cleanSupplies = new ArrayCollection();
        $this->cleanLinens = new ArrayCollection();
        $this->cleanHousekeepers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function getScheduled(): ?\DateTimeInterface
    {
        return $this->scheduled;
    }

    public function setScheduled(?\DateTimeInterface $scheduled): static
    {
        $this->scheduled = $scheduled;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?\DateTimeInterface $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): static
    {
        $this->end = $end;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

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
     * @return Collection<int, CleanPhoto>
     */
    public function getCleanPhotos(): Collection
    {
        return $this->cleanPhotos;
    }

    public function addCleanPhoto(CleanPhoto $cleanPhoto): static
    {
        if (!$this->cleanPhotos->contains($cleanPhoto)) {
            $this->cleanPhotos->add($cleanPhoto);
            $cleanPhoto->setClean($this);
        }

        return $this;
    }

    public function removeCleanPhoto(CleanPhoto $cleanPhoto): static
    {
        if ($this->cleanPhotos->removeElement($cleanPhoto)) {
            // set the owning side to null (unless already changed)
            if ($cleanPhoto->getClean() === $this) {
                $cleanPhoto->setClean(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CleanSupply>
     */
    public function getCleanSupplies(): Collection
    {
        return $this->cleanSupplies;
    }

    public function addCleanSupply(CleanSupply $cleanSupply): static
    {
        if (!$this->cleanSupplies->contains($cleanSupply)) {
            $this->cleanSupplies->add($cleanSupply);
            $cleanSupply->setClean($this);
        }

        return $this;
    }

    public function removeCleanSupply(CleanSupply $cleanSupply): static
    {
        if ($this->cleanSupplies->removeElement($cleanSupply)) {
            // set the owning side to null (unless already changed)
            if ($cleanSupply->getClean() === $this) {
                $cleanSupply->setClean(null);
            }
        }

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
            $cleanLinen->setClean($this);
        }

        return $this;
    }

    public function removeCleanLinen(CleanLinen $cleanLinen): static
    {
        if ($this->cleanLinens->removeElement($cleanLinen)) {
            // set the owning side to null (unless already changed)
            if ($cleanLinen->getClean() === $this) {
                $cleanLinen->setClean(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CleanHousekeeper>
     */
    public function getCleanHousekeepers(): Collection
    {
        return $this->cleanHousekeepers;
    }

    public function addCleanHousekeeper(CleanHousekeeper $cleanHousekeeper): static
    {
        if (!$this->cleanHousekeepers->contains($cleanHousekeeper)) {
            $this->cleanHousekeepers->add($cleanHousekeeper);
            $cleanHousekeeper->setClean($this);
        }

        return $this;
    }

    public function removeCleanHousekeeper(CleanHousekeeper $cleanHousekeeper): static
    {
        if ($this->cleanHousekeepers->removeElement($cleanHousekeeper)) {
            // set the owning side to null (unless already changed)
            if ($cleanHousekeeper->getClean() === $this) {
                $cleanHousekeeper->setClean(null);
            }
        }

        return $this;
    }

    public function getInspectionNotes(): ?string
    {
        return $this->inspectionNotes;
    }

    public function setInspectionNotes(?string $inspectionNotes): static
    {
        $this->inspectionNotes = $inspectionNotes;

        return $this;
    }

    public function getSuppliesClaimed(): ?\DateTimeInterface
    {
        return $this->suppliesClaimed;
    }

    public function setSuppliesClaimed(?\DateTimeInterface $suppliesClaimed): static
    {
        $this->suppliesClaimed = $suppliesClaimed;

        return $this;
    }
}
