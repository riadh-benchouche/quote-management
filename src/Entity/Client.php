<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom de l\'entreprise ne peut pas être vide.')]
    #[Assert\Length(max: 100, maxMessage: 'Le nom de l\'entreprise ne peut pas dépasser 100 caractères.')]
    private ?string $company_name = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Email(message: 'L\'adresse e-mail n\'est pas valide.')]
    #[Assert\Unique(message: 'L\'adresse e-mail est déjà utilisée.')]
    #[Assert\Length(max: 100, maxMessage: 'L\'adresse e-mail ne peut pas dépasser 100 caractères.')]
    private ?string $email = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100, maxMessage: 'Le nom ne peut pas dépasser 100 caractères.')]
    private ?string $lastname = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100, maxMessage: 'Le prénom ne peut pas dépasser 100 caractères.')]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'L\'adresse ne peut pas dépasser 255 caractères.')]
    private ?string $address = null;

    #[ORM\Column(length: 5, nullable: true)]
    #[Assert\Length(max: 5, maxMessage: 'Le code postal ne peut pas dépasser 5 caractères.')]
    private ?string $zipcode = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100, maxMessage: 'La ville ne peut pas dépasser 100 caractères.')]
    private ?string $city = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20, maxMessage: 'Le numéro de téléphone ne peut pas dépasser 20 caractères.')]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Devis::class)]
    private Collection $devis;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Facture::class)]
    private Collection $factures;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis->add($devi);
            $devi->setClient($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getClient() === $this) {
                $devi->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setClient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->company_name; // Remplacez "nom" par le nom de l'attribut que vous souhaitez afficher
    }
}
