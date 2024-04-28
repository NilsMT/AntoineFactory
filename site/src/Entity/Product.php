<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $nom;

    #[ORM\Column]
    private float $prix;

    #[ORM\Column]
    private int $promotion;

    #[ORM\Column]
    private int $nbVentes;

    #[ORM\Column]
    private int $stock;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(length: 255)]
    private string $materiau;

    #[ORM\Column]
    private float $longueur;

    #[ORM\Column]
    private float $largeur;

    #[ORM\Column]
    private float $epaisseur;
    
    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'products_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(length: 255)]
    private string $categorie;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'id_product', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $paniers;

    public function __construct(){
        $this->nbVentes=0;
        $this->paniers = new ArrayCollection();
    }
    static function decroissant($articleA, $articleB) {
        return ($articleA->prix * (1-($articleA->promotion / 100))) < ($articleB->prix * (1-($articleB->promotion / 100)));
    }

    static function croissant($articleA, $articleB) {
        return ($articleA->prix * (1-($articleA->promotion / 100))) > ($articleB->prix * (1-($articleB->promotion / 100)));
    }

    static function meilleures_ventes($articleA, $articleB) {
        return $articleA->nbVentes < $articleB->nbVentes;
    }

    static function promo($articleA, $articleB) {
        return $articleA->promotion < $articleB->promotion;
    }
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPromotion(): int
    {
        return $this->promotion;
    }

    public function setPromotion(int $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getNbVentes(): int
    {
        return $this->nbVentes;
    }

    public function setNbVentes(int $nbVentes): static
    {
        $this->nbVentes = $nbVentes;

        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMateriau(): string
    {
        return $this->materiau;
    }

    public function setMateriau(string $materiau): static
    {
        $this->materiau = $materiau;

        return $this;
    }

    public function getLongueur(): float
    {
        return $this->longueur;
    }

    public function setLongueur(float $longueur): static
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getLargeur(): float
    {
        return $this->largeur;
    }

    public function setLargeur(float $largeur): static
    {
        $this->largeur = $largeur;

        return $this;
    }

    public function getEpaisseur(): float
    {
        return $this->epaisseur;
    }

    public function setEpaisseur(float $epaisseur): static
    {
        $this->epaisseur = $epaisseur;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    /*
        Prend un produit en entrée.
        Parcours le panier de l'utilisateur pour voir si il est déjà dedans.
        Return true -> le produit était déjà dans le panier (sa quantité est mise à jour à celle choisie)
        Return false -> le produit n'était pas dans le panier et l'ajoute.
    */

    public function addPanier(Panier $panier): bool
    {
        for ($i = 0; $i < count($this->paniers); $i++){ 
            if ($this->paniers[$i]->getIdProduct()->getId() === $panier->getIdProduct()->getId()) {
                
                return true;
            } 
        }
        $this->paniers->add($panier);
        $panier->setIdProduct($this);

        return false;
    }

    /*
        Prend un produit en paramètre.
        Retire le produit du panier de l'utilisateur si il est dedans.
    */

    public function removePanier(Panier $panier): static
    {
        for ($i = 0; $i < count($this->paniers); $i++){ 
            if ($this->paniers[$i]->getIdProduct()->getId() === $panier->getIdProduct()->getId()) {
                $this->paniers->removeElement($this->paniers[$i]);
                $panier->setIdProduct(null);
            } 
        }
        return $this;
    }
}
