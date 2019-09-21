<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProvinceRepository")
 */
class Province
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Demand", mappedBy="province")
     */
    private $demand;

    public function __construct()
    {
        $this->demand = new ArrayCollection();
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

    /**
     * @return Collection|Demand[]
     */
    public function getDemand(): Collection
    {
        return $this->demand;
    }

    public function addDemand(Demand $demand): self
    {
        if (!$this->demand->contains($demand)) {
            $this->demand[] = $demand;
            $demand->setProvince($this);
        }

        return $this;
    }

    public function removeDemand(Demand $demand): self
    {
        if ($this->demand->contains($demand)) {
            $this->demand->removeElement($demand);
            // set the owning side to null (unless already changed)
            if ($demand->getProvince() === $this) {
                $demand->setProvince(null);
            }
        }

        return $this;
    }
}
