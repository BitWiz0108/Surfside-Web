<?php

namespace App\Entity;

use App\Repository\CleanLinenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CleanLinenRepository::class)]
class CleanLinen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cleanLinens')]
    private ?Clean $clean = null;

    #[ORM\ManyToOne(inversedBy: 'cleanLinens')]
    private ?Linen $linen = null;

    #[ORM\Column(nullable: true)]
    private ?int $units = null;

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

    public function getLinen(): ?Linen
    {
        return $this->linen;
    }

    public function setLinen(?Linen $linen): static
    {
        $this->linen = $linen;

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
}
