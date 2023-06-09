<?php

namespace App\Entity;

use App\Repository\ProduitFactureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitFactureRepository::class)]
class ProduitFacture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'produitFacture')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Le champ facture ne peut pas être vide')]
    private ?Facture $facture = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Le champ produit ne peut pas être vide')]
    private ?Produit $produit = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le champ quantity ne peut pas être vide')]
    #[Assert\GreaterThan(value: 0, message: 'Le champ quantité doit être supérieur à zéro')]
    private ?int $quantity = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le champ tva ne peut pas être vide')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Le champ tva doit être supérieur ou égal à zéro')]
    private ?float $tva = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le champ price ne peut pas être vide')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Le champ prix doit être supérieur ou égal à zéro')]
    private ?float $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        return $this;
    }
}
