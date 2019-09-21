<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandRepository")
 */
class Demand
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, max=255, minMessage= "Le titre doit faire plus de 10 caractères !",
     *     maxMessage="Le titre ne peut pas faire plus de 255 caractères !")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=100, minMessage="La description de la demande doit faire au moins 100 caractères !")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="demands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Execution", mappedBy="Demand")
     */
    private $executions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ImageFront;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="demands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Province", inversedBy="demand")
     * @ORM\JoinColumn(nullable=false)
     */
    private $province;

    public function __construct()
    {
        $this->executions = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|Execution[]
     */
    public function getExecutions(): Collection
    {
        return $this->executions;
    }

    public function addExecution(Execution $execution): self
    {
        if (!$this->executions->contains($execution)) {
            $this->executions[] = $execution;
            $execution->setDemand($this);
        }

        return $this;
    }

    public function removeExecution(Execution $execution): self
    {
        if ($this->executions->contains($execution)) {
            $this->executions->removeElement($execution);
            // set the owning side to null (unless already changed)
            if ($execution->getDemand() === $this) {
                $execution->setDemand(null);
            }
        }

        return $this;
    }

    public function getImageFront(): ?string
    {
        return $this->ImageFront;
    }

    public function setImageFront(?string $ImageFront): self
    {
        $this->ImageFront = $ImageFront;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getProvince(): ?Province
    {
        return $this->province;
    }

    public function setProvince(?Province $province): self
    {
        $this->province = $province;

        return $this;
    }


}
