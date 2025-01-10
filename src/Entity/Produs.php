<?php

namespace App\Entity;

use App\Repository\ProdusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdusRepository::class)]
class Produs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nume = null;

    #[ORM\Column]
    private ?float $Stoc = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Observatii = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStoc(): ?float
    {
        return $this->Stoc;
    }

    public function setStoc(float $Stoc): static
    {
        $this->Stoc = $Stoc;

        return $this;
    }

    public function getObservatii(): ?string
    {
        return $this->Observatii;
    }

    public function setObservatii(?string $Observatii): static
    {
        $this->Observatii = $Observatii;

        return $this;
    }
}
