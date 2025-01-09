<?php

namespace App\Entity;

use App\Repository\LocatieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocatieRepository::class)]
class Locatie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Nr_aparat = null;

    #[ORM\Column(length: 255)]
    private ?string $Nume = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNrAparat(): ?int
    {
        return $this->Nr_aparat;
    }

    public function setNrAparat(int $Nr_aparat): static
    {
        $this->Nr_aparat = $Nr_aparat;

        return $this;
    }

    public function getNume(): ?string
    {
        return $this->Nume;
    }

    public function setNume(string $Nume): static
    {
        $this->Nume = $Nume;

        return $this;
    }
}
