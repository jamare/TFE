<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Table(name="provider")
 * @ORM\Entity(repositoryClass="App\Repository\ProviderRepository")
 */
class Provider extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $tva;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="Veuillez renseigner une URL valide")
     */
    private $web;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="providers")
     */
    private $Service;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Execution", mappedBy="provider")
     */
    private $executions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="provider", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->Service = new ArrayCollection();
        $this->executions = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(?string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): self
    {
        $this->web = $web;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getService(): Collection
    {
        return $this->Service;
    }

    public function addService(Category $service): self
    {
        if (!$this->Service->contains($service)) {
            $this->Service[] = $service;
        }

        return $this;
    }

    public function removeService(Category $service): self
    {
        if ($this->Service->contains($service)) {
            $this->Service->removeElement($service);
        }

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
            $execution->setProvider($this);
        }

        return $this;
    }

    public function removeExecution(Execution $execution): self
    {
        if ($this->executions->contains($execution)) {
            $this->executions->removeElement($execution);
            // set the owning side to null (unless already changed)
            if ($execution->getProvider() === $this) {
                $execution->setProvider(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProvider($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getProvider() === $this) {
                $comment->setProvider(null);
            }
        }

        return $this;
    }

    public function getAvgRatings(){
        // calcul de la somme des avis
        // array_reduce pour réduire le tableau de note à une seule valeur
        $sum = array_reduce($this->comments->toArray(), function ($total, $comment){
            return $total + $comment->getRating();
        },0);

        // calcul de la moyenne
        if(count($this->comments) > 0) return $sum / count($this->comments);

        return 0;
    }
}
