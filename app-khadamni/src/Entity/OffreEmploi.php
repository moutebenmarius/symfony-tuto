<?php

namespace App\Entity;

use App\Repository\OffreEmploiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffreEmploiRepository::class)
 */
class OffreEmploi
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
     * @ORM\Column(type="string", length=255)
     */
    private $salaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeEmploi;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ajouterEn;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="offreEmplois")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recruteur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity=Postuler::class, mappedBy="offre")
     */
    private $postulers;

    /**
     * @ORM\OneToMany(targetEntity=Entretien::class, mappedBy="offre")
     */
    private $entretiens;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estSupprime;

    public function __construct()
    {
        $this->postulers = new ArrayCollection();
        $this->entretiens = new ArrayCollection();
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

    public function getSalaire(): ?string
    {
        return $this->salaire;
    }

    public function setSalaire(string $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getTypeEmploi(): ?string
    {
        return $this->typeEmploi;
    }

    public function setTypeEmploi(string $typeEmploi): self
    {
        $this->typeEmploi = $typeEmploi;

        return $this;
    }

    public function getAjouterEn(): ?\DateTimeInterface
    {
        return $this->ajouterEn;
    }

    public function setAjouterEn(\DateTimeInterface $ajouterEn): self
    {
        $this->ajouterEn = $ajouterEn;

        return $this;
    }

    public function getRecruteur(): ?Utilisateur
    {
        return $this->recruteur;
    }

    public function setRecruteur(?Utilisateur $recruteur): self
    {
        $this->recruteur = $recruteur;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Postuler>
     */
    public function getPostulers(): Collection
    {
        return $this->postulers;
    }

    public function addPostuler(Postuler $postuler): self
    {
        if (!$this->postulers->contains($postuler)) {
            $this->postulers[] = $postuler;
            $postuler->setOffre($this);
        }

        return $this;
    }

    public function removePostuler(Postuler $postuler): self
    {
        if ($this->postulers->removeElement($postuler)) {
            // set the owning side to null (unless already changed)
            if ($postuler->getOffre() === $this) {
                $postuler->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Entretien>
     */
    public function getEntretiens(): Collection
    {
        return $this->entretiens;
    }

    public function addEntretien(Entretien $entretien): self
    {
        if (!$this->entretiens->contains($entretien)) {
            $this->entretiens[] = $entretien;
            $entretien->setOffre($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): self
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getOffre() === $this) {
                $entretien->setOffre(null);
            }
        }

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
}
