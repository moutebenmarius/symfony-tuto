<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 */
class Matiere
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
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="matieres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

    /**
     * @ORM\OneToMany(targetEntity=Affectation::class, mappedBy="matiere")
     */
    private $affectations;

    /**
     * @ORM\OneToMany(targetEntity=PartieMatiere::class, mappedBy="matiere")
     */
    private $partieMatieres;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estArchive;

    /**
     * @ORM\OneToMany(targetEntity=Evaluation::class, mappedBy="matiere")
     */
    private $evaluations;

    /**
     * @ORM\OneToMany(targetEntity=Seance::class, mappedBy="matiere")
     */
    private $seances;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
        $this->partieMatieres = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function __toString()
    {
        return $this->libelle;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setMatiere($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getMatiere() === $this) {
                $affectation->setMatiere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PartieMatiere>
     */
    public function getPartieMatieres(): Collection
    {
        return $this->partieMatieres;
    }

    public function addPartieMatiere(PartieMatiere $partieMatiere): self
    {
        if (!$this->partieMatieres->contains($partieMatiere)) {
            $this->partieMatieres[] = $partieMatiere;
            $partieMatiere->setMatiere($this);
        }

        return $this;
    }

    public function removePartieMatiere(PartieMatiere $partieMatiere): self
    {
        if ($this->partieMatieres->removeElement($partieMatiere)) {
            // set the owning side to null (unless already changed)
            if ($partieMatiere->getMatiere() === $this) {
                $partieMatiere->setMatiere(null);
            }
        }

        return $this;
    }

    public function getEstArchive(): ?bool
    {
        return $this->estArchive;
    }

    public function setEstArchive(bool $estArchive): self
    {
        $this->estArchive = $estArchive;

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setMatiere($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getMatiere() === $this) {
                $evaluation->setMatiere(null);
            }
        }

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
            $seance->setMatiere($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getMatiere() === $this) {
                $seance->setMatiere(null);
            }
        }

        return $this;
    }
}
