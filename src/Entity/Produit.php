<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le champ name est obligatoire')]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le champ prixHt est obligatoire')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Le champ prixHt doit être supérieur ou égal à zéro')]
    private ?float $prixHt = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le champ tva est obligatoire')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Le champ tva doit être supérieur ou égal à zéro')]
    private ?float $tva = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le champ montant est obligatoire')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Le champ montant doit être supérieur ou égal à zéro')]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[Assert\NotNull(message: 'Le champ categorie ne peut pas être vide')]
    private ?Categorie $categorie = null;

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
