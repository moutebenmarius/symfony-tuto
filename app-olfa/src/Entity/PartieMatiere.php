<?php

namespace App\Entity;

use App\Repository\PartieMatiereRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartieMatiereRepository::class)
 */
class PartieMatiere
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="partieMatieres")
     */
    private $enseignant;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="partieMatieres")
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity=CahierTexte::class, inversedBy="partieMatieres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cahierTexte;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estVuParDirecteur;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fichier;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $travauxAFaire;

    

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnseignant(): ?Utilisateur
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Utilisateur $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getCahierTexte(): ?CahierTexte
    {
        return $this->cahierTexte;
    }

    public function setCahierTexte(?CahierTexte $cahierTexte): self
    {
        $this->cahierTexte = $cahierTexte;

        return $this;
    }

    public function getEstVuParDirecteur(): ?bool
    {
        return $this->estVuParDirecteur;
    }

    public function setEstVuParDirecteur(bool $estVuParDirecteur): self
    {
        $this->estVuParDirecteur = $estVuParDirecteur;

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

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getTravauxAFaire(): ?string
    {
        return $this->travauxAFaire;
    }

    public function setTravauxAFaire(?string $travauxAFaire): self
    {
        $this->travauxAFaire = $travauxAFaire;

        return $this;
    }

   
    
}
