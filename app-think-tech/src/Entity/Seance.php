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
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="seances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSeance;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estTermine;

    /**
     * @ORM\OneToMany(targetEntity=RessourcePedagogique::class, mappedBy="seance")
     */
    private $ressourcePedagogiques;

    public function __construct()
    {
        $this->ressourcePedagogiques = new ArrayCollection();
    }

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

    public function getDateSeance(): ?\DateTimeInterface
    {
        return $this->dateSeance;
    }

    public function setDateSeance(\DateTimeInterface $dateSeance): self
    {
        $this->dateSeance = $dateSeance;

        return $this;
    }

    public function getEstTermine(): ?bool
    {
        return $this->estTermine;
    }

    public function setEstTermine(bool $estTermine): self
    {
        $this->estTermine = $estTermine;

        return $this;
    }

    /**
     * @return Collection<int, RessourcePedagogique>
     */
    public function getRessourcePedagogiques(): Collection
    {
        return $this->ressourcePedagogiques;
    }

    public function addRessourcePedagogique(RessourcePedagogique $ressourcePedagogique): self
    {
        if (!$this->ressourcePedagogiques->contains($ressourcePedagogique)) {
            $this->ressourcePedagogiques[] = $ressourcePedagogique;
            $ressourcePedagogique->setSeance($this);
        }

        return $this;
    }

    public function removeRessourcePedagogique(RessourcePedagogique $ressourcePedagogique): self
    {
        if ($this->ressourcePedagogiques->removeElement($ressourcePedagogique)) {
            // set the owning side to null (unless already changed)
            if ($ressourcePedagogique->getSeance() === $this) {
                $ressourcePedagogique->setSeance(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}
