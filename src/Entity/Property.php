<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $postalcode = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $latitude = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $longitude = null;

    #[ORM\Column(nullable: true)]
    private ?int $bedrooms = null;

    #[ORM\Column(nullable: true)]
    private ?int $bathrooms = null;

    #[ORM\Column(nullable: true)]
    private ?int $square_feet = null;

    #[ORM\Column(nullable: true)]
    private ?int $kings = null;

    #[ORM\Column(nullable: true)]
    private ?int $queens = null;

    #[ORM\Column(nullable: true)]
    private ?int $twins = null;

    #[ORM\Column(nullable: true)]
    private ?int $towels = null;

    #[ORM\Column(nullable: true)]
    private ?int $hand_towels = null;

    #[ORM\Column(nullable: true)]
    private ?int $wash_cloths = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $instructions = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $door_code = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    private ?Customer $customer = null;

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: PropertyPhoto::class)]
    private Collection $propertyPhotos;

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Clean::class)]
    private Collection $cleans;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified = null;

    #[ORM\Column(nullable: true)]
    private ?float $cost = null;

    public function __construct()
    {
        $this->propertyPhotos = new ArrayCollection();
        $this->cleans = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(?int $bedrooms): static
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getBathrooms(): ?int
    {
        return $this->bathrooms;
    }

    public function setBathrooms(?int $bathrooms): static
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    public function getSquareFeet(): ?int
    {
        return $this->square_feet;
    }

    public function setSquareFeet(?int $square_feet): static
    {
        $this->square_feet = $square_feet;

        return $this;
    }

    public function getKings(): ?int
    {
        return $this->kings;
    }

    public function setKings(?int $kings): static
    {
        $this->kings = $kings;

        return $this;
    }

    public function getQueens(): ?int
    {
        return $this->queens;
    }

    public function setQueens(?int $queens): static
    {
        $this->queens = $queens;

        return $this;
    }

    public function getTwins(): ?int
    {
        return $this->twins;
    }

    public function setTwins(?int $twins): static
    {
        $this->twins = $twins;

        return $this;
    }

    public function getTowels(): ?int
    {
        return $this->towels;
    }

    public function setTowels(?int $towels): static
    {
        $this->towels = $towels;

        return $this;
    }

    public function getHandTowels(): ?int
    {
        return $this->hand_towels;
    }

    public function setHandTowels(?int $hand_towels): static
    {
        $this->hand_towels = $hand_towels;

        return $this;
    }

    public function getWashCloths(): ?int
    {
        return $this->wash_cloths;
    }

    public function setWashCloths(?int $wash_cloths): static
    {
        $this->wash_cloths = $wash_cloths;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(?string $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getDoorCode(): ?string
    {
        return $this->door_code;
    }

    public function setDoorCode(?string $door_code): static
    {
        $this->door_code = $door_code;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, PropertyPhoto>
     */
    public function getPropertyPhotos(): Collection
    {
        return $this->propertyPhotos;
    }

    public function addPropertyPhoto(PropertyPhoto $propertyPhoto): static
    {
        if (!$this->propertyPhotos->contains($propertyPhoto)) {
            $this->propertyPhotos->add($propertyPhoto);
            $propertyPhoto->setProperty($this);
        }

        return $this;
    }

    public function removePropertyPhoto(PropertyPhoto $propertyPhoto): static
    {
        if ($this->propertyPhotos->removeElement($propertyPhoto)) {
            // set the owning side to null (unless already changed)
            if ($propertyPhoto->getProperty() === $this) {
                $propertyPhoto->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Clean>
     */
    public function getCleans(): Collection
    {
        return $this->cleans;
    }

    public function addClean(Clean $clean): static
    {
        if (!$this->cleans->contains($clean)) {
            $this->cleans->add($clean);
            $clean->setProperty($this);
        }

        return $this;
    }

    public function removeClean(Clean $clean): static
    {
        if ($this->cleans->removeElement($clean)) {
            // set the owning side to null (unless already changed)
            if ($clean->getProperty() === $this) {
                $clean->setProperty(null);
            }
        }

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

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): static
    {
        $this->cost = $cost;

        return $this;
    }
}
