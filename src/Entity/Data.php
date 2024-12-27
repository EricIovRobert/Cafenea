<?php

namespace App\Entity;

use App\Repository\DataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataRepository::class)]
class Data
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Ziua = null;

    /**
     * @var Collection<int, ClientZiua>
     */
    #[ORM\OneToMany(targetEntity: ClientZiua::class, mappedBy: 'data')]
    private Collection $clientZiuas;

    public function __construct()
    {
        $this->clientZiuas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZiua(): ?\DateTimeInterface
    {
        return $this->Ziua;
    }

    public function setZiua(\DateTimeInterface $Ziua): static
    {
        $this->Ziua = $Ziua;

        return $this;
    }

    /**
     * @return Collection<int, ClientZiua>
     */
    public function getClientZiuas(): Collection
    {
        return $this->clientZiuas;
    }

    public function addClientZiua(ClientZiua $clientZiua): static
    {
        if (!$this->clientZiuas->contains($clientZiua)) {
            $this->clientZiuas->add($clientZiua);
            $clientZiua->setData($this);
        }

        return $this;
    }

    public function removeClientZiua(ClientZiua $clientZiua): static
    {
        if ($this->clientZiuas->removeElement($clientZiua)) {
            // set the owning side to null (unless already changed)
            if ($clientZiua->getData() === $this) {
                $clientZiua->setData(null);
            }
        }

        return $this;
    }
}
