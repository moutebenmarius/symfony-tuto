<?php

namespace App\Entity;

use App\Repository\ApprenantFormationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApprenantFormationRepository::class)
 */
class ApprenantFormation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="apprenantFormations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="apprenantFormations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apprenant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInscription;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $estAccepte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getApprenant(): ?Utilisateur
    {
        return $this->apprenant;
    }

    public function setApprenant(?Utilisateur $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getEstAccepte(): ?bool
    {
        return $this->estAccepte;
    }

    public function setEstAccepte(?bool $estAccepte): self
    {
        $this->estAccepte = $estAccepte;

        return $this;
    }
}
