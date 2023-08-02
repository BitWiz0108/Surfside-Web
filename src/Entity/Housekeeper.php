<?php

namespace App\Entity;

use App\Repository\HousekeeperRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HousekeeperRepository::class)]
class Housekeeper
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $postalcode = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $employee_id = null;

    #[ORM\OneToOne(inversedBy: 'housekeeper', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'housekeeper', targetEntity: CleanHousekeeper::class)]
    private Collection $cleanHousekeepers;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $iNineFront = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $iNineBack = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idFront = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idBack = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    public function __construct()
    {
        $this->cleanHousekeepers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalcode(?string $postalcode): static
    {
        $this->postalcode = $postalcode;

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

    public function getEmployeeId(): ?string
    {
        return $this->employee_id;
    }

    public function setEmployeeId(?string $employee_id): static
    {
        $this->employee_id = $employee_id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
            $cleanHousekeeper->setHousekeeper($this);
        }

        return $this;
    }

    public function removeCleanHousekeeper(CleanHousekeeper $cleanHousekeeper): static
    {
        if ($this->cleanHousekeepers->removeElement($cleanHousekeeper)) {
            // set the owning side to null (unless already changed)
            if ($cleanHousekeeper->getHousekeeper() === $this) {
                $cleanHousekeeper->setHousekeeper(null);
            }
        }

        return $this;
    }

    public function getINineFront(): ?string
    {
        return $this->iNineFront;
    }

    public function setINineFront(?string $iNineFront): static
    {
        $this->iNineFront = $iNineFront;

        return $this;
    }

    public function getINineBack(): ?string
    {
        return $this->iNineBack;
    }

    public function setINineBack(?string $iNineBack): static
    {
        $this->iNineBack = $iNineBack;

        return $this;
    }

    public function getIdFront(): ?string
    {
        return $this->idFront;
    }

    public function setIdFront(?string $idFront): static
    {
        $this->idFront = $idFront;

        return $this;
    }

    public function getIdBack(): ?string
    {
        return $this->idBack;
    }

    public function setIdBack(?string $idBack): static
    {
        $this->idBack = $idBack;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
