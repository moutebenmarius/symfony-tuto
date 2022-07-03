<?php

namespace App\Entity;

use App\Repository\RessourcePedagogiqueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RessourcePedagogiqueRepository::class)
 */
class RessourcePedagogique
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
     * @ORM\ManyToOne(targetEntity=Seance::class, inversedBy="ressourcePedagogiques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $seance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInsertion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fichierUrl;

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

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): self
    {
        $this->seance = $seance;

        return $this;
    }

    public function getDateInsertion(): ?\DateTimeInterface
    {
        return $this->dateInsertion;
    }

    public function setDateInsertion(\DateTimeInterface $dateInsertion): self
    {
        $this->dateInsertion = $dateInsertion;

        return $this;
    }

    public function getFichierUrl(): ?string
    {
        return $this->fichierUrl;
    }

    public function setFichierUrl(string $fichierUrl): self
    {
        $this->fichierUrl = $fichierUrl;

        return $this;
    }
}
