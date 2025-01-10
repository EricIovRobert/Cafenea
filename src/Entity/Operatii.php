<?php

namespace App\Entity;

use App\Repository\OperatiiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperatiiRepository::class)]
class Operatii
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Data = null;

    #[ORM\Column]
    private ?float $stoc_act = null;

    /**
     * @var Collection<int, Produs>
     */
    #[ORM\ManyToMany(targetEntity: Produs::class, inversedBy: 'operatiis')]
    private Collection $prod;

    #[ORM\Column]
    private ?float $nr = null;

    public function __construct()
    {
        $this->prod = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->Data;
    }

    public function setData(\DateTimeInterface $Data): static
    {
        $this->Data = $Data;

        return $this;
    }

    public function getStocAct(): ?float
    {
        return $this->stoc_act;
    }

    public function setStocAct(float $stoc_act): static
    {
        $this->stoc_act = $stoc_act;

        return $this;
    }

    /**
     * @return Collection<int, Produs>
     */
    public function getProd(): Collection
    {
        return $this->prod;
    }

    public function addProd(Produs $prod): static
    {
        if (!$this->prod->contains($prod)) {
            $this->prod->add($prod);
        }

        return $this;
    }

    public function removeProd(Produs $prod): static
    {
        $this->prod->removeElement($prod);

        return $this;
    }

    public function getNr(): ?float
    {
        return $this->nr;
    }

    public function setNr(float $nr): static
    {
        $this->nr = $nr;

        return $this;
    }
}
