<?php

namespace App\Entity;

use App\Repository\EntretienRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntretienRepository::class)
 */
class Entretien
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=OffreEmploi::class, inversedBy="entretiens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEntretien;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="entretiens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $demandeur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estSupprime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lienEntretien;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateEntretien(): ?\DateTimeInterface
    {
        return $this->dateEntretien;
    }

    public function setDateEntretien(\DateTimeInterface $dateEntretien): self
    {
        $this->dateEntretien = $dateEntretien;

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

    public function getDemandeur(): ?Utilisateur
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Utilisateur $demandeur): self
    {
        $this->demandeur = $demandeur;

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

    public function getLienEntretien(): ?string
    {
        return $this->lienEntretien;
    }

    public function setLienEntretien(string $lienEntretien): self
    {
        $this->lienEntretien = $lienEntretien;

        return $this;
    }
}
