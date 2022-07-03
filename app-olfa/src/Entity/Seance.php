<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeanceRepository::class)
 */
class Seance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSeance;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="seances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * @ORM\OneToMany(targetEntity=Eleve::class, mappedBy="seance")
     */
    private $elevesAbsents;

    public function __construct()
    {
        $this->elevesAbsents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSeance(): ?\DateTimeInterface
    {
        return $this->dateSeance;
    }

    public function setDateSeance(\DateTimeInterface $dateSeance): self
    {
        $this->dateSeance = $dateSeance;

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

    /**
     * @return Collection<int, Eleve>
     */
    public function getElevesAbsents(): Collection
    {
        return $this->elevesAbsents;
    }

    public function addElevesAbsent(Eleve $elevesAbsent): self
    {
        if (!$this->elevesAbsents->contains($elevesAbsent)) {
            $this->elevesAbsents[] = $elevesAbsent;
            $elevesAbsent->setSeance($this);
        }

        return $this;
    }

    public function removeElevesAbsent(Eleve $elevesAbsent): self
    {
        if ($this->elevesAbsents->removeElement($elevesAbsent)) {
            // set the owning side to null (unless already changed)
            if ($elevesAbsent->getSeance() === $this) {
                $elevesAbsent->setSeance(null);
            }
        }

        return $this;
    }
}
