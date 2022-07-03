<?php

namespace App\Entity;

use App\Repository\RecommendationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecommendationRepository::class)
 */
class Recommendation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=DossierMedical::class, inversedBy="recommendations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dossier;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="recommendations")
     */
    private $medecin;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="recommndationsLabo")
     */
    private $labo;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="recommndationsPharmacie")
     */
    private $pharmacie;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="recommendationsCentre")
     */
    private $centreImagerie;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $feedback_medecin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossier(): ?DossierMedical
    {
        return $this->dossier;
    }

    public function setDossier(?DossierMedical $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getMedecin(): ?Utilisateur
    {
        return $this->medecin;
    }

    public function setMedecin(?Utilisateur $medecin): self
    {
        $this->medecin = $medecin;

        return $this;
    }

    public function getLabo(): ?Utilisateur
    {
        return $this->labo;
    }

    public function setLabo(?Utilisateur $labo): self
    {
        $this->labo = $labo;

        return $this;
    }

    public function getPharmacie(): ?Utilisateur
    {
        return $this->pharmacie;
    }

    public function setPharmacie(?Utilisateur $pharmacie): self
    {
        $this->pharmacie = $pharmacie;

        return $this;
    }

    public function getCentreImagerie(): ?Utilisateur
    {
        return $this->centreImagerie;
    }

    public function setCentreImagerie(?Utilisateur $centreImagerie): self
    {
        $this->centreImagerie = $centreImagerie;

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

    public function getFeedbackMedecin(): ?string
    {
        return $this->feedback_medecin;
    }

    public function setFeedbackMedecin(?string $feedback_medecin): self
    {
        $this->feedback_medecin = $feedback_medecin;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
