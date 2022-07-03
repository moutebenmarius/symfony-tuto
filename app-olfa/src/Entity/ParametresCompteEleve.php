<?php

namespace App\Entity;

use App\Repository\ParametresCompteEleveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParametresCompteEleveRepository::class)
 */
class ParametresCompteEleve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $de;

    /**
     * @ORM\Column(type="date")
     */
    private $a;

    

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDe(): ?\DateTimeInterface
    {
        return $this->de;
    }

    public function setDe(\DateTimeInterface $de): self
    {
        $this->de = $de;

        return $this;
    }

    public function getA(): ?\DateTimeInterface
    {
        return $this->a;
    }

    public function setA(\DateTimeInterface $a): self
    {
        $this->a = $a;

        return $this;
    }

    
}
