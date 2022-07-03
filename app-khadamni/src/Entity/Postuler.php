<?php

namespace App\Entity;

use App\Repository\PostulerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostulerRepository::class)
 */
class Postuler
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="postulers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $demandeur;

    /**
     * @ORM\ManyToOne(targetEntity=OffreEmploi::class, inversedBy="postulers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePostule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estSupprime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemandeur(): ?Utilisateur
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Utilisateur $demandeur): self
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getOffre(): ?OffreEmploi
    {
        return $this->offre;
    }

    public function setOffre(?OffreEmploi $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getDatePostule(): ?\DateTimeInterface
    {
        return $this->datePostule;
    }

    public function setDatePostule(\DateTimeInterface $datePostule): self
    {
        $this->datePostule = $datePostule;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEstSupprime(): ?bool
    {
        return $this->estSupprime;
    }

    public function setEstSupprime(bool $estSupprime): self
    {
        $this->estSupprime = $estSupprime;

        return $this;
    }
}
