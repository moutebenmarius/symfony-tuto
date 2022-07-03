<?php

namespace App\Entity;

use App\Repository\DossierMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DossierMedicalRepository::class)
 */
class DossierMedical
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
    private $dateConsultation;

    /**
     * @ORM\ManyToOne(targetEntity=RendezVous::class, inversedBy="dossierMedicals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rendezVous;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ordonnance;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $analyses;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $radio;

    /**
     * @ORM\Column(type="text")
     */
    private $ficheDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Recommendation::class, mappedBy="dossier")
     */
    private $recommendations;

    public function __construct()
    {
        $this->recommendations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateConsultation(): ?\DateTimeInterface
    {
        return $this->dateConsultation;
    }

    public function setDateConsultation(\DateTimeInterface $dateConsultation): self
    {
        $this->dateConsultation = $dateConsultation;

        return $this;
    }

    public function getRendezVous(): ?RendezVous
    {
        return $this->rendezVous;
    }

    public function setRendezVous(?RendezVous $rendezVous): self
    {
        $this->rendezVous = $rendezVous;

        return $this;
    }

    public function getOrdonnance(): ?string
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?string $ordonnance): self
    {
        $this->ordonnance = $ordonnance;

        return $this;
    }

    public function getAnalyses(): ?string
    {
        return $this->analyses;
    }

    public function setAnalyses(?string $analyses): self
    {
        $this->analyses = $analyses;

        return $this;
    }

    public function getRadio(): ?string
    {
        return $this->radio;
    }

    public function setRadio(?string $radio): self
    {
        $this->radio = $radio;

        return $this;
    }

    public function getFicheDescription(): ?string
    {
        return $this->ficheDescription;
    }

    public function setFicheDescription(string $ficheDescription): self
    {
        $this->ficheDescription = $ficheDescription;

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

    /**
     * @return Collection<int, Recommendation>
     */
    public function getRecommendations(): Collection
    {
        return $this->recommendations;
    }

    public function addRecommendation(Recommendation $recommendation): self
    {
        if (!$this->recommendations->contains($recommendation)) {
            $this->recommendations[] = $recommendation;
            $recommendation->setDossier($this);
        }

        return $this;
    }

    public function removeRecommendation(Recommendation $recommendation): self
    {
        if ($this->recommendations->removeElement($recommendation)) {
            // set the owning side to null (unless already changed)
            if ($recommendation->getDossier() === $this) {
                $recommendation->setDossier(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}
