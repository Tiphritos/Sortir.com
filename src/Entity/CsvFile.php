<?php

namespace App\Entity;

use App\Repository\CsvFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CsvFileRepository::class)]
class CsvFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $participantfile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipantfile(): ?string
    {
        return $this->participantfile;
    }

    public function setParticipantfile(string $participantfile): self
    {
        $this->participantfile = $participantfile;

        return $this;
    }
}
