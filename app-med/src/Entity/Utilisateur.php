<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(fields={"nomUtilisateur"}, message="There is already an account with this nomUtilisateur")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $nomUtilisateur;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="text")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeroTelephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $specialite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseEmail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photoUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitterUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $youtubeUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=HoraireTravail::class, mappedBy="utilisateur")
     */
    private $horaireTravails;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $degre;

    /**
     * @ORM\OneToMany(targetEntity=RendezVous::class, mappedBy="medecin")
     */
    private $rendezVousesMedecin;

    /**
     * @ORM\OneToMany(targetEntity=RendezVous::class, mappedBy="patient")
     */
    private $rendezVousesPatient;

    /**
     * @ORM\OneToMany(targetEntity=Recommendation::class, mappedBy="medecin")
     */
    private $recommendations;

    /**
     * @ORM\OneToMany(targetEntity=Recommendation::class, mappedBy="labo")
     */
    private $recommndationsLabo;

    /**
     * @ORM\OneToMany(targetEntity=Recommendation::class, mappedBy="pharmacie")
     */
    private $recommndationsPharmacie;

    /**
     * @ORM\OneToMany(targetEntity=Recommendation::class, mappedBy="centreImagerie")
     */
    private $recommendationsCentre;

    /**
     * @ORM\OneToMany(targetEntity=Medicament::class, mappedBy="pharmacie")
     */
    private $medicaments;

    public function __construct()
    {
        $this->horaireTravails = new ArrayCollection();
        $this->rendezVousesMedecin = new ArrayCollection();
        $this->rendezVousesPatient = new ArrayCollection();
        $this->recommendations = new ArrayCollection();
        $this->recommndationsLabo = new ArrayCollection();
        $this->recommndationsPharmacie = new ArrayCollection();
        $this->recommendationsCentre = new ArrayCollection();
        $this->medicaments = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(string $nomUtilisateur): self
    {
        $this->nomUtilisateur = $nomUtilisateur;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->nomUtilisateur;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword():? string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumeroTelephone(): ?string
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(string $numeroTelephone): self
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getAdresseEmail(): ?string
    {
        return $this->adresseEmail;
    }

    public function setAdresseEmail(string $adresseEmail): self
    {
        $this->adresseEmail = $adresseEmail;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?string $photoUrl): self
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getFacebookUrl(): ?string
    {
        return $this->facebookUrl;
    }

    public function setFacebookUrl(?string $facebookUrl): self
    {
        $this->facebookUrl = $facebookUrl;

        return $this;
    }

    public function getTwitterUrl(): ?string
    {
        return $this->twitterUrl;
    }

    public function setTwitterUrl(?string $twitterUrl): self
    {
        $this->twitterUrl = $twitterUrl;

        return $this;
    }

    public function getYoutubeUrl(): ?string
    {
        return $this->youtubeUrl;
    }

    public function setYoutubeUrl(string $youtubeUrl): self
    {
        $this->youtubeUrl = $youtubeUrl;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, HoraireTravail>
     */
    public function getHoraireTravails(): Collection
    {
        return $this->horaireTravails;
    }

    public function addHoraireTravail(HoraireTravail $horaireTravail): self
    {
        if (!$this->horaireTravails->contains($horaireTravail)) {
            $this->horaireTravails[] = $horaireTravail;
            $horaireTravail->setUtilisateur($this);
        }

        return $this;
    }

    public function removeHoraireTravail(HoraireTravail $horaireTravail): self
    {
        if ($this->horaireTravails->removeElement($horaireTravail)) {
            // set the owning side to null (unless already changed)
            if ($horaireTravail->getUtilisateur() === $this) {
                $horaireTravail->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getDegre(): ?string
    {
        return $this->degre;
    }

    public function setDegre(?string $degre): self
    {
        $this->degre = $degre;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVousesMedecin(): Collection
    {
        return $this->rendezVousesMedecin;
    }

    public function addRendezVousesMedecin(RendezVous $rendezVousesMedecin): self
    {
        if (!$this->rendezVousesMedecin->contains($rendezVousesMedecin)) {
            $this->rendezVousesMedecin[] = $rendezVousesMedecin;
            $rendezVousesMedecin->setMedecin($this);
        }

        return $this;
    }

    public function removeRendezVousesMedecin(RendezVous $rendezVousesMedecin): self
    {
        if ($this->rendezVousesMedecin->removeElement($rendezVousesMedecin)) {
            // set the owning side to null (unless already changed)
            if ($rendezVousesMedecin->getMedecin() === $this) {
                $rendezVousesMedecin->setMedecin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVousesPatient(): Collection
    {
        return $this->rendezVousesPatient;
    }

    public function addRendezVousesPatient(RendezVous $rendezVousesPatient): self
    {
        if (!$this->rendezVousesPatient->contains($rendezVousesPatient)) {
            $this->rendezVousesPatient[] = $rendezVousesPatient;
            $rendezVousesPatient->setPatient($this);
        }

        return $this;
    }

    public function removeRendezVousesPatient(RendezVous $rendezVousesPatient): self
    {
        if ($this->rendezVousesPatient->removeElement($rendezVousesPatient)) {
            // set the owning side to null (unless already changed)
            if ($rendezVousesPatient->getPatient() === $this) {
                $rendezVousesPatient->setPatient(null);
            }
        }

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
            $recommendation->setMedecin($this);
        }

        return $this;
    }

    public function removeRecommendation(Recommendation $recommendation): self
    {
        if ($this->recommendations->removeElement($recommendation)) {
            // set the owning side to null (unless already changed)
            if ($recommendation->getMedecin() === $this) {
                $recommendation->setMedecin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recommendation>
     */
    public function getRecommndationsLabo(): Collection
    {
        return $this->recommndationsLabo;
    }

    public function addRecommndationsLabo(Recommendation $recommndationsLabo): self
    {
        if (!$this->recommndationsLabo->contains($recommndationsLabo)) {
            $this->recommndationsLabo[] = $recommndationsLabo;
            $recommndationsLabo->setLabo($this);
        }

        return $this;
    }

    public function removeRecommndationsLabo(Recommendation $recommndationsLabo): self
    {
        if ($this->recommndationsLabo->removeElement($recommndationsLabo)) {
            // set the owning side to null (unless already changed)
            if ($recommndationsLabo->getLabo() === $this) {
                $recommndationsLabo->setLabo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recommendation>
     */
    public function getRecommndationsPharmacie(): Collection
    {
        return $this->recommndationsPharmacie;
    }

    public function addRecommndationsPharmacie(Recommendation $recommndationsPharmacie): self
    {
        if (!$this->recommndationsPharmacie->contains($recommndationsPharmacie)) {
            $this->recommndationsPharmacie[] = $recommndationsPharmacie;
            $recommndationsPharmacie->setPharmacie($this);
        }

        return $this;
    }

    public function removeRecommndationsPharmacie(Recommendation $recommndationsPharmacie): self
    {
        if ($this->recommndationsPharmacie->removeElement($recommndationsPharmacie)) {
            // set the owning side to null (unless already changed)
            if ($recommndationsPharmacie->getPharmacie() === $this) {
                $recommndationsPharmacie->setPharmacie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recommendation>
     */
    public function getRecommendationsCentre(): Collection
    {
        return $this->recommendationsCentre;
    }

    public function addRecommendationsCentre(Recommendation $recommendationsCentre): self
    {
        if (!$this->recommendationsCentre->contains($recommendationsCentre)) {
            $this->recommendationsCentre[] = $recommendationsCentre;
            $recommendationsCentre->setCentreImagerie($this);
        }

        return $this;
    }

    public function removeRecommendationsCentre(Recommendation $recommendationsCentre): self
    {
        if ($this->recommendationsCentre->removeElement($recommendationsCentre)) {
            // set the owning side to null (unless already changed)
            if ($recommendationsCentre->getCentreImagerie() === $this) {
                $recommendationsCentre->setCentreImagerie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->prenom.' '.$this->nom;
    }

    /**
     * @return Collection<int, Medicament>
     */
    public function getMedicaments(): Collection
    {
        return $this->medicaments;
    }

    public function addMedicament(Medicament $medicament): self
    {
        if (!$this->medicaments->contains($medicament)) {
            $this->medicaments[] = $medicament;
            $medicament->setPharmacie($this);
        }

        return $this;
    }

    public function removeMedicament(Medicament $medicament): self
    {
        if ($this->medicaments->removeElement($medicament)) {
            // set the owning side to null (unless already changed)
            if ($medicament->getPharmacie() === $this) {
                $medicament->setPharmacie(null);
            }
        }

        return $this;
    }

    
}
