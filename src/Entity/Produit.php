<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $prixHt = null;

    #[ORM\Column]
    private ?float $tva = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToMany(targetEntity: Devis::class, inversedBy: 'produits')]
    private Collection $devis;

    #[ORM\ManyToMany(targetEntity: Facture::class, inversedBy: 'produits')]
    private Collection $factures;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $categorie = null;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrixHt(): ?float
    {
        return $this->prixHt;
    }

    public function setPrixHt(float $prixHt): self
    {
        $this->prixHt = $prixHt;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): self
    {
        $this->tva = $tva;

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
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        $this->devis->removeElement($devi);

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
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        $this->factures->removeElement($facture);

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name; // Remplacez "nom" par le nom de l'attribut que vous souhaitez afficher
    }
}
