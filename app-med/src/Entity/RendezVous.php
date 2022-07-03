<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RendezVousRepository::class)
 */
class RendezVous
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="rendezVousesMedecin")
     */
    private $medecin;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="rendezVousesPatient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\Column(type="date")
     */
    private $dateRendezvous;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAjout;

    /**
     * @ORM\OneToMany(targetEntity=DossierMedical::class, mappedBy="rendezVous")
     */
    private $dossierMedicals;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $controle;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateControle;

    public function __construct()
    {
        $this->dossierMedicals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPatient(): ?Utilisateur
    {
        return $this->patient;
    }

    public function setPatient(?Utilisateur $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDateRendezvous(): ?\DateTimeInterface
    {
        return $this->dateRendezvous;
    }

    public function setDateRendezvous(\DateTimeInterface $dateRendezvous): self
    {
        $this->dateRendezvous = $dateRendezvous;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * @return Collection<int, DossierMedical>
     */
    public function getDossierMedicals(): Collection
    {
        return $this->dossierMedicals;
    }

    public function addDossierMedical(DossierMedical $dossierMedical): self
    {
        if (!$this->dossierMedicals->contains($dossierMedical)) {
            $this->dossierMedicals[] = $dossierMedical;
            $dossierMedical->setRendezVous($this);
        }

        return $this;
    }

    public function removeDossierMedical(DossierMedical $dossierMedical): self
    {
        if ($this->dossierMedicals->removeElement($dossierMedical)) {
            // set the owning side to null (unless already changed)
            if ($dossierMedical->getRendezVous() === $this) {
                $dossierMedical->setRendezVous(null);
            }
        }

        return $this;
    }

    public function getControle(): ?bool
    {
        return $this->controle;
    }

    public function setControle(?bool $controle): self
    {
        $this->controle = $controle;

        return $this;
    }

    public function getDateControle(): ?\DateTimeInterface
    {
        return $this->dateControle;
    }

    public function setDateControle(?\DateTimeInterface $dateControle): self
    {
        $this->dateControle = $dateControle;

        return $this;
    }
}
