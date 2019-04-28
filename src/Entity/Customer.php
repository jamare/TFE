<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $FirstName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Demand", mappedBy="customer", orphanRemoval=true)
     */
    private $demands;

    public function getFullName(){
        return "{$this->FirstName} {$this->name}";
    }

    public function __construct()
    {
        $this->demands = new ArrayCollection();
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

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    /**
     * @return Collection|Demand[]
     */
    public function getDemands(): Collection
    {
        return $this->demands;
    }

    public function addDemand(Demand $demand): self
    {
        if (!$this->demands->contains($demand)) {
            $this->demands[] = $demand;
            $demand->setCustomer($this);
        }

        return $this;
    }

    public function removeDemand(Demand $demand): self
    {
        if ($this->demands->contains($demand)) {
            $this->demands->removeElement($demand);
            // set the owning side to null (unless already changed)
            if ($demand->getCustomer() === $this) {
                $demand->setCustomer(null);
            }
        }

        return $this;
    }


}
