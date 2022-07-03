<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 */
class Classe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="classes")
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="classes")
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity=Eleve::class, mappedBy="classe")
     */
    private $eleves;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estArchive;

    /**
     * @ORM\OneToMany(targetEntity=Matiere::class, mappedBy="classe")
     */
    private $matieres;

    /**
     * @ORM\OneToMany(targetEntity=Affectation::class, mappedBy="classe")
     */
    private $affectations;

    /**
     * @ORM\OneToMany(targetEntity=PartieMatiere::class, mappedBy="classe")
     */
    private $partieMatieres;

    /**
     * @ORM\OneToMany(targetEntity=Programme::class, mappedBy="classe")
     */
    private $programmes;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $this->matieres = new ArrayCollection();
        $this->affectations = new ArrayCollection();
        $this->partieMatieres = new ArrayCollection();
        $this->programmes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }
    public function __toString()
    {
        $section = "";
        if($this->section){
            $section = $this->section->getSection();
        }
        
        return $this->niveau->getNiveau().' '.$section;
    }
    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection<int, Eleve>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleve $elefe): self
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves[] = $elefe;
            $elefe->setClasse($this);
        }

        return $this;
    }

    public function removeElefe(Eleve $elefe): self
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getClasse() === $this) {
                $elefe->setClasse(null);
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
     * @return Collection<int, Matiere>
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
            $matiere->setClasse($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getClasse() === $this) {
                $matiere->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setClasse($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getClasse() === $this) {
                $affectation->setClasse(null);
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
            $partieMatiere->setClasse($this);
        }

        return $this;
    }

    public function removePartieMatiere(PartieMatiere $partieMatiere): self
    {
        if ($this->partieMatieres->removeElement($partieMatiere)) {
            // set the owning side to null (unless already changed)
            if ($partieMatiere->getClasse() === $this) {
                $partieMatiere->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes[] = $programme;
            $programme->setClasse($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): self
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getClasse() === $this) {
                $programme->setClasse(null);
            }
        }

        return $this;
    }
}
