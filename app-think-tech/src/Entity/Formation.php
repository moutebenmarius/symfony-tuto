<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

   

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrHeures;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formateur;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=Seance::class, mappedBy="formation")
     */
    private $seances;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantFormation::class, mappedBy="formation")
     */
    private $apprenantFormations;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->apprenantFormations = new ArrayCollection();
    }

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

   

    public function getNbrHeures(): ?int
    {
        return $this->nbrHeures;
    }

    public function setNbrHeures(int $nbrHeures): self
    {
        $this->nbrHeures = $nbrHeures;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFormateur(): ?Utilisateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Utilisateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): self
    {
        if (!$this->seances->contains($seance)) {
            $this->seances[] = $seance;
            $seance->setFormation($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getFormation() === $this) {
                $seance->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApprenantFormation>
     */
    public function getApprenantFormations(): Collection
    {
        return $this->apprenantFormations;
    }

    public function addApprenantFormation(ApprenantFormation $apprenantFormation): self
    {
        if (!$this->apprenantFormations->contains($apprenantFormation)) {
            $this->apprenantFormations[] = $apprenantFormation;
            $apprenantFormation->setFormation($this);
        }

        return $this;
    }

    public function removeApprenantFormation(ApprenantFormation $apprenantFormation): self
    {
        if ($this->apprenantFormations->removeElement($apprenantFormation)) {
            // set the owning side to null (unless already changed)
            if ($apprenantFormation->getFormation() === $this) {
                $apprenantFormation->setFormation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
    }

    
}
