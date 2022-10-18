<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank]
    #[Assert\GreaterThan('today')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    #[Assert\NotBlank]
    #[ORM\Column(nullable: true)]
    private ?int $duree = null;

    #[Assert\NotBlank]
    #[Assert\GreaterThan('today')]
    #[Assert\Expression("this.getDateDebut() > this.getDateCloture()")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_cloture = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $nb_inscriptions_max = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description_infos = null;

    #[ORM\Column(length: 255, nullable: true, options:['default'=>'SortieDefault.gif'])]
    private ?string $url_photo = null;

    #[Vich\UploadableField(mapping:"profile_pics", fileNameProperty:'url_photo')]
    private ?File $imageFile = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etats_no_etat = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieux_no_lieu = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $organisateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site_organisateur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;


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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->date_cloture;
    }

    public function setDateCloture(\DateTimeInterface $date_cloture): self
    {
        $this->date_cloture = $date_cloture;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nb_inscriptions_max;
    }

    public function setNbInscriptionsMax(int $nb_inscriptions_max): self
    {
        $this->nb_inscriptions_max = $nb_inscriptions_max;

        return $this;
    }

    public function getDescriptionInfos(): ?string
    {
        return $this->description_infos;
    }

    public function setDescriptionInfos(?string $description_infos): self
    {
        $this->description_infos = $description_infos;

        return $this;
    }

    public function getUrlPhoto(): ?string
    {
        return $this->url_photo;
    }

    public function setUrlPhoto(?string $url_photo): self
    {
        $this->url_photo = $url_photo;

        return $this;
    }

    public function getEtatsNoEtat(): ?Etat
    {
        return $this->etats_no_etat;
    }

    public function setEtatsNoEtat(?Etat $etats_no_etat): self
    {
        $this->etats_no_etat = $etats_no_etat;

        return $this;
    }

    public function getLieuxNoLieu(): ?lieu
    {
        return $this->lieux_no_lieu;
    }

    public function setLieuxNoLieu(?lieu $lieux_no_lieu): self
    {
        $this->lieux_no_lieu = $lieux_no_lieu;

        return $this;
    }

    public function getOrganisateur(): ?participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getSiteOrganisateur(): ?site
    {
        return $this->site_organisateur;
    }

    public function setSiteOrganisateur(?site $site_organisateur): self
    {
        $this->site_organisateur = $site_organisateur;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
