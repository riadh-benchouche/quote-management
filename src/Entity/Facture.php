<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Le champ date de facture est obligatoire')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Le champ échéance de facture est obligatoire')]
    private ?\DateTimeInterface $echeance = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le champ statut de facture est obligatoire')]
    private ?string $status = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le champ montant de facture est obligatoire')]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[Assert\NotBlank(message: 'Le champ client de facture est obligatoire')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Devis $devis = null;

    #[ORM\OneToMany(mappedBy: 'facture', targetEntity: ProduitFacture::class, cascade: ['persist'])]
    private Collection $produitFacture;

    public function __construct()
    {
        $this->produitFacture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEcheance(): ?\DateTimeInterface
    {
        return $this->echeance;
    }

    public function setEcheance(\DateTimeInterface $echeance): self
    {
        $this->echeance = $echeance;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;

        return $this;
    }

    /**
     * @return Collection<int, ProduitFacture>
     */
    public function getProduitFacture(): Collection
    {
        return $this->produitFacture;
    }

    public function addProduitFacture(ProduitFacture $produitFacture): self
    {
        if (!$this->produitFacture->contains($produitFacture)) {
            $this->produitFacture->add($produitFacture);
            $produitFacture->setFacture($this);
        }

        return $this;
    }

    public function removeProduitFacture(ProduitFacture $produitFacture): self
    {
        if ($this->produitFacture->removeElement($produitFacture)) {
            // set the owning side to null (unless already changed)
            if ($produitFacture->getFacture() === $this) {
                $produitFacture->setFacture(null);
            }
        }

        return $this;
    }
}
