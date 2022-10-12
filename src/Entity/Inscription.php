<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_inscription = null;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sortie $sortie_id = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $participant_id = null;


    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getSortieId(): ?Sortie
    {
        return $this->sortie_id;
    }

    public function setSortieId(?Sortie $sortie_id): self
    {
        $this->sortie_id = $sortie_id;

        return $this;
    }

    public function getParticipantId(): ?Participant
    {
        return $this->participant_id;
    }

    public function setParticipantId(?Participant $participant_id): self
    {
        $this->participant_id = $participant_id;

        return $this;
    }
}
