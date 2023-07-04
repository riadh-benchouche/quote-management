<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(length: 50, options: ["default" => "brouillon"])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: Facture::class)]
    private Collection $factures;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: ProduitDevis::class, orphanRemoval: true)]
    private Collection $produitDevis;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
        $this->produitDevis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
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


    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
            $facture->setDevis($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getDevis() === $this) {
                $facture->setDevis(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProduitDevis>
     */
    public function getProduitDevis(): Collection
    {
        return $this->produitDevis;
    }

    public function addProduitDevi(ProduitDevis $produitDevi): self
    {
        if (!$this->produitDevis->contains($produitDevi)) {
            $this->produitDevis->add($produitDevi);
            $produitDevi->setDevis($this);
        }

        return $this;
    }

    public function removeProduitDevi(ProduitDevis $produitDevi): self
    {
        if ($this->produitDevis->removeElement($produitDevi)) {
            // set the owning side to null (unless already changed)
            if ($produitDevi->getDevis() === $this) {
                $produitDevi->setDevis(null);
            }
        }

        return $this;
    }
}
