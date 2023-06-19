<?php

namespace App\Entity;

use App\Repository\CleanSupplyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CleanSupplyRepository::class)]
class CleanSupply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cleanSupplies')]
    private ?Clean $clean = null;

    #[ORM\ManyToOne(inversedBy: 'cleanSupplies')]
    private ?Supply $supply = null;

    #[ORM\Column(nullable: true)]
    private ?float $units = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClean(): ?Clean
    {
        return $this->clean;
    }

    public function setClean(?Clean $clean): static
    {
        $this->clean = $clean;

        return $this;
    }

    public function getSupply(): ?Supply
    {
        return $this->supply;
    }

    public function setSupply(?Supply $supply): static
    {
        $this->supply = $supply;

        return $this;
    }

    public function getUnits(): ?float
    {
        return $this->units;
    }

    public function setUnits(?float $units): static
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
}
