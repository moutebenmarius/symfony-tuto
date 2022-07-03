<?php

namespace App\Entity;

use App\Repository\CahierTexteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CahierTexteRepository::class)
 */
class CahierTexte
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
     * @ORM\OneToOne(targetEntity=Classe::class, cascade={"persist", "remove"})
     */
    private $classe;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estArchive;

    /**
     * @ORM\OneToMany(targetEntity=PartieMatiere::class, mappedBy="cahierTexte")
     */
    private $partieMatieres;

    public function __construct()
    {
        $this->partieMatieres = new ArrayCollection();
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
     * @return Collection<int, PartieMatiere>
     */
    public function getPartieMatieres(): Collection
    {
        return $this->partieMatieres;
    }

    public function __toString()
    {
        return $this->libelle;
    }

    public function addPartieMatiere(PartieMatiere $partieMatiere): self
    {
        if (!$this->partieMatieres->contains($partieMatiere)) {
            $this->partieMatieres[] = $partieMatiere;
            $partieMatiere->setCahierTexte($this);
        }

        return $this;
    }

    public function removePartieMatiere(PartieMatiere $partieMatiere): self
    {
        if ($this->partieMatieres->removeElement($partieMatiere)) {
            // set the owning side to null (unless already changed)
            if ($partieMatiere->getCahierTexte() === $this) {
                $partieMatiere->setCahierTexte(null);
            }
        }

        return $this;
    }

   
}
