<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    private ?string $plainPassword = null;
    private ?string $newPlainPassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password ;

    #[ORM\Column(length: 255)]
    private string $nom;

    #[ORM\Column(length: 255)]
    private string $prenom;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $panier;

    public function __construct(){
        $this->roles=["ROLE_USER"];
        $this->panier = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
    public function getNewPlainPassword(): string
    {
        return $this->newPlainPassword;
    }

    public function setNewPlainPassword(string $newPlainPassword): static
    {
        $this->newPlainPassword = $newPlainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    /* 
        Prend un produit en paramètre.
        Parcours le panier de l'utilisateur pour voir si il est déjà dedans.
        Return true -> le produit était déjà dans le panier (sa quantité est mise à jour à celle choisie)
        Return false -> le produit n'était pas dans le panier et l'ajoute.
    */
    public function addPanier(Panier $panier): bool
    {
        for ($i = 0; $i < count($this->panier); $i++){ 
            if ($this->panier[$i]->getIdProduct()->getId() === $panier->getIdProduct()->getId()) {
                $this->panier[$i]->setQuantite($panier->getQuantite());
                return true;
            } 
        }
        $this->panier->add($panier);
        $panier->setIdUser($this);

        return false;
    }

    /*
        Prend un produit en paramètre.
        Retire le produit du panier de l'utilisateur si il est dedans.
    */


    public function removePanier(Panier $panier): static{
        
        for ($i = 0; $i < count($this->panier); $i++){ 
            if ($this->panier[$i] !== null
            && $this->panier[$i]->getIdProduct() !== null
            && $panier !== null
            && $panier->getIdProduct() !== null
            && $this->panier[$i]->getIdProduct()->getId() === $panier->getIdProduct()->getId()){
                $this->panier->removeElement($this->panier[$i]);
                $panier->setIdUser(null);
            } 
        }
        return $this;
    }


}
