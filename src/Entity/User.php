<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"provider" = "Provider", "customer" = "Customer"})
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Nom d'utilisateur déjà utilisé ( email )"
 * )
 */
abstract class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner un email valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas correctement confirmé votre mot de passes !")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $banished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PostalCode", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PostalCode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locality", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locality;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $siteRoles;

    public function __construct()
    {
        $this->siteRoles = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegistration(): ?\DateTimeInterface
    {
        return $this->registration;
    }

    public function setRegistration(\DateTimeInterface $registration): self
    {
        $this->registration = $registration;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBanished(): ?bool
    {
        return $this->banished;
    }

    public function setBanished(bool $banished): self
    {
        $this->banished = $banished;

        return $this;
    }

    public function getPostalCode(): ?PostalCode
    {
        return $this->PostalCode;
    }

    public function setPostalCode(?PostalCode $PostalCode): self
    {
        $this->PostalCode = $PostalCode;

        return $this;
    }

    public function getLocality(): ?Locality
    {
        return $this->locality;
    }

    public function setLocality(?Locality $locality): self
    {
        $this->locality = $locality;

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->siteRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        /*dump($roles);
        die();*/

        return $roles;
    }

    public function getSalt(){

    }

    public function getUsername(){
        return $this->email;
    }

    public function eraseCredentials()
    {

    }

    /**
     * @return Collection|Role[]
     */
    public function getSiteRoles(): Collection
    {
        return $this->siteRoles;
    }

    public function addSiteRole(Role $siteRole): self
    {
        if( is_null( $this->siteRoles)){
            $this->siteRoles = new ArrayCollection();

        }
        if (!$this->siteRoles->contains($siteRole)) {
            $this->siteRoles[] = $siteRole;
            $siteRole->addUser($this);
        }

        return $this;

    }

    public function removeSiteRole(Role $siteRole): self
    {
        if ($this->siteRoles->contains($siteRole)) {
            $this->siteRoles->removeElement($siteRole);
            $siteRole->removeUser($this);
        }

        return $this;
    }

}
