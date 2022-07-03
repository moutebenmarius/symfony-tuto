<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MedicamentRepository::class)
 */
class Medicament
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="medicaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pharmacie;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estExiste;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPharmacie(): ?Utilisateur
    {
        return $this->pharmacie;
    }

    public function setPharmacie(?Utilisateur $pharmacie): self
    {
        $this->pharmacie = $pharmacie;

        return $this;
    }

    public function getEstExiste(): ?bool
    {
        return $this->estExiste;
    }

    public function setEstExiste(bool $estExiste): self
    {
        $this->estExiste = $estExiste;

        return $this;
    }
}
