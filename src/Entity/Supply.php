<?php

namespace App\Entity;

use App\Repository\SupplyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplyRepository::class)]
class Supply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?float $units = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $units_measurement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified = null;

    #[ORM\OneToMany(mappedBy: 'supply', targetEntity: CleanSupply::class)]
    private Collection $cleanSupplies;

    public function __construct()
    {
        $this->cleanSupplies = new ArrayCollection();
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

    public function getUnits(): ?float
    {
        return $this->units;
    }

    public function setUnits(float $units): static
    {
        $this->units = $units;

        return $this;
    }

    public function getUnitsMeasurement(): ?string
    {
        return $this->units_measurement;
    }

    public function setUnitsMeasurement(?string $units_measurement): static
    {
        $this->units_measurement = $units_measurement;

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
            $cleanSupply->setSupply($this);
        }

        return $this;
    }

    public function removeCleanSupply(CleanSupply $cleanSupply): static
    {
        if ($this->cleanSupplies->removeElement($cleanSupply)) {
            // set the owning side to null (unless already changed)
            if ($cleanSupply->getSupply() === $this) {
                $cleanSupply->setSupply(null);
            }
        }

        return $this;
    }
}
