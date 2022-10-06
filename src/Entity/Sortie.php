<?php

namespace App\Entity;

use App\Repository\SortiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SortiesRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datecloture = null;

    #[ORM\Column]
    private ?int $nbinscriptionsmax = null;

    #[ORM\Column(length: 500)]
    private ?string $descriptioninfos = null;

    #[ORM\Column(length: 500)]
    private ?string $urlPhoto = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    private ?Lieu $lieux = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $etablissement = null;

    #[ORM\ManyToOne(inversedBy: 'organisateur')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $participant = null;

    #[ORM\ManyToMany(targetEntity: Participant::class, mappedBy: 'estInscrit')]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDatecloture(): ?\DateTimeInterface
    {
        return $this->datecloture;
    }

    public function setDatecloture(\DateTimeInterface $datecloture): self
    {
        $this->datecloture = $datecloture;

        return $this;
    }

    public function getNbinscriptionsmax(): ?int
    {
        return $this->nbinscriptionsmax;
    }

    public function setNbinscriptionsmax(int $nbinscriptionsmax): self
    {
        $this->nbinscriptionsmax = $nbinscriptionsmax;

        return $this;
    }

    public function getDescriptioninfos(): ?string
    {
        return $this->descriptioninfos;
    }

    public function setDescriptioninfos(string $descriptioninfos): self
    {
        $this->descriptioninfos = $descriptioninfos;

        return $this;
    }

    public function getUrlPhoto(): ?string
    {
        return $this->urlPhoto;
    }

    public function setUrlPhoto(string $urlPhoto): self
    {
        $this->urlPhoto = $urlPhoto;

        return $this;
    }

    public function getetat(): ?Etat
    {
        return $this->etat;
    }

    public function setetat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLieux(): ?Lieu
    {
        return $this->lieux;
    }

    public function setLieux(?Lieu $lieux): self
    {
        $this->lieux = $lieux;

        return $this;
    }

    public function getEtablissement(): ?Site
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Site $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    //toString participant
    public function __toString()
    {
        return $this->participant;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addEstInscrit($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeEstInscrit($this);
        }

        return $this;
    }


}
