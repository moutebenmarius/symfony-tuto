<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TournoiRepository::class)
 */
class Tournoi
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="tournois")
     * @ORM\JoinColumn(nullable=false)
     */
    private $arbitre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $candidat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vainqueur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $score;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTournoi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $candidat2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArbitre(): ?Utilisateur
    {
        return $this->arbitre;
    }

    public function setArbitre(?Utilisateur $arbitre): self
    {
        $this->arbitre = $arbitre;

        return $this;
    }

    public function getCandidat(): ?string
    {
        return $this->candidat;
    }

    public function setCandidat(string $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getVainqueur(): ?string
    {
        return $this->vainqueur;
    }

    public function setVainqueur(string $vainqueur): self
    {
        $this->vainqueur = $vainqueur;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getDateTournoi(): ?\DateTimeInterface
    {
        return $this->dateTournoi;
    }

    public function setDateTournoi(\DateTimeInterface $dateTournoi): self
    {
        $this->dateTournoi = $dateTournoi;

        return $this;
    }

    public function getCandidat2(): ?string
    {
        return $this->candidat2;
    }

    public function setCandidat2(string $candidat2): self
    {
        $this->candidat2 = $candidat2;

        return $this;
    }
}
