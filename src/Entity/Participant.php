<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
#[Vich\Uploadable]
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre nom est trop long. Sa longueur maximale est de 100 caractères.',
    )]
    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min:3 ,
        max: 25,
        minMessage: 'Votre pseudo est trop court. Sa longueur minimale est de 2 caractères.',
        maxMessage: 'Votre pseudo est trop long. Sa longueur maximale est de 25 caractères.',
    )]
    #[ORM\Column(length: 25, unique: true)]
    private ?string $pseudo = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Votre prenom est trop long. Sa longueur maximale est de 100 caractères.',
    )]
    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $telephone = null;

    #[Assert\Email]
    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column]
    private ?string $mot_de_passe = null;

    #[ORM\Column]
    private ?bool $administrateur = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column]
    private array $roles = [];


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $sites_no_site = null;

    #[ORM\OneToMany(mappedBy: 'participant_id', targetEntity: Inscription::class, orphanRemoval: true)]
    private Collection $inscriptions;

    #[ORM\Column(length: 255, nullable: true, options: ['default'=>'ProfilDefault.png'])]
    private ?string $url_photo = null;

    #[Vich\UploadableField(mapping:"profile_pics", fileNameProperty:'url_photo')]
    private ?File $imageFile = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->sorties_no_sortie = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesNoSortie(): Collection
    {
        return $this->sorties_no_sortie;
    }

    public function getSitesNoSite(): ?site
    {
        return $this->sites_no_site;
    }

    public function setSitesNoSite(?site $sites_no_site): self
    {
        $this->sites_no_site = $sites_no_site;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setPassword(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

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

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setParticipantId($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getParticipantId() === $this) {
                $inscription->setParticipantId(null);
            }
        }

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

    public function __serialize(): array    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'pseudo' => $this->pseudo,
            'prenom' => $this->prenom,
            'telephone' => $this->telephone,
            'mail' => $this->mail,
            'motDePasse' => $this->mot_de_passe,
            'administrateur' => $this->administrateur,
            'actif' => $this->actif,
            'roles' => $this->roles,
            'sites_no_site' => $this->sites_no_site,
            'inscriptions' => $this->inscriptions,
            'url_photo' => $this->url_photo,
//            'imageFile' => $this->imageFile,
            'updatedAt' => $this->updatedAt
        ];
    }

    public function __unserialize(array $serialized): void{
        $this->id = $serialized['id'];
        $this->mail = $serialized['mail'];
        $this->nom = $serialized['nom'];
        $this->pseudo = $serialized['pseudo'];
        $this->prenom = $serialized['prenom'];
        $this->telephone = $serialized['telephone'];
        $this->mot_de_passe = $serialized['motDePasse'];
        $this->administrateur = $serialized['administrateur'];
        $this->actif = $serialized['actif'];
        $this->roles = $serialized['roles'];
        $this->sites_no_site = $serialized['sites_no_site'];
        $this->inscriptions = $serialized['inscriptions'];
        $this->url_photo = $serialized['url_photo'];
        //$this->imageFile = $serialized['imageFile'];
        $this->updatedAt = $serialized['updatedAt'];

    }
}
