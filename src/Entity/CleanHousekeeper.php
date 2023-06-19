<?php

namespace App\Entity;

use App\Repository\CleanHousekeeperRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CleanHousekeeperRepository::class)]
class CleanHousekeeper
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cleanHousekeepers')]
    private ?Clean $clean = null;

    #[ORM\ManyToOne(inversedBy: 'cleanHousekeepers')]
    private ?Housekeeper $housekeeper = null;

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

    public function getHousekeeper(): ?Housekeeper
    {
        return $this->housekeeper;
    }

    public function setHousekeeper(?Housekeeper $housekeeper): static
    {
        $this->housekeeper = $housekeeper;

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
