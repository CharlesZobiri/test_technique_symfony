<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit ne peut pas être vide.")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Le nom du produit doit contenir au minimum {2} caractères.")]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide.")]
    #[Assert\GreaterThan(value: 0, message: "Le prix doit être supérieur à 0.")]
    private ?float $prix = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[Assert\NotBlank(message: "La catégorie ne peut pas être vide.")]
    private ?Categorie $categorie = null;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nom' => $this->getNom(),
            'description' => $this->getDescription(),
            'prix' => $this->getPrix(),
            'dateCreation' => $this->getDateCreation()?->format('Y-m-d H:i:s'),
            'categorie' => $this->getCategorie()?->getNom() ?? null,
        ];
    }
}