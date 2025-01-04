<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Citire_anterioara = null;

    #[ORM\Column]
    private ?int $Citire_actuala = null;

    #[ORM\Column(nullable: true)]
    private ?int $Probe = null;

    #[ORM\Column]
    private ?float $Pret = null;

    #[ORM\Column(nullable: true)]
    private ?int $CafeaCovim = null;

    #[ORM\Column(nullable: true)]
    private ?int $CafeaLavazza = null;

    #[ORM\Column(nullable: true)]
    private ?int $Zahar = null;

    #[ORM\Column(nullable: true)]
    private ?int $Lapte = null;

    #[ORM\Column(nullable: true)]
    private ?int $Ceai = null;

    #[ORM\Column(nullable: true)]
    private ?int $Solubil = null;

    #[ORM\Column(nullable: true)]
    private ?int $Pahare_plastic = null;

    #[ORM\Column(nullable: true)]
    private ?int $Pahare_carton = null;

    #[ORM\Column(nullable: true)]
    private ?int $Palete = null;

    /**
     * @var Collection<int, ClientZiua>
     */
    #[ORM\OneToMany(targetEntity: ClientZiua::class, mappedBy: 'client')]
    private Collection $clientZiuas;

    #[ORM\Column(length: 50)]
    private ?string $Nume = null;

    public function __construct()
    {
        $this->clientZiuas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCitireAnterioara(): ?int
    {
        return $this->Citire_anterioara;
    }

    public function setCitireAnterioara(int $Citire_anterioara): static
    {
        $this->Citire_anterioara = $Citire_anterioara;

        return $this;
    }

    public function getCitireActuala(): ?int
    {
        return $this->Citire_actuala;
    }

    public function setCitireActuala(int $Citire_actuala): static
    {
        $this->Citire_actuala = $Citire_actuala;

        return $this;
    }

    public function getProbe(): ?int
    {
        return $this->Probe;
    }

    public function setProbe(?int $Probe): static
    {
        $this->Probe = $Probe;

        return $this;
    }

    public function getPret(): ?float
    {
        return $this->Pret;
    }

    public function setPret(float $Pret): static
    {
        $this->Pret = $Pret;

        return $this;
    }

    public function getCafeaCovim(): ?int
    {
        return $this->CafeaCovim;
    }

    public function setCafeaCovim(?int $CafeaCovim): static
    {
        $this->CafeaCovim = $CafeaCovim;

        return $this;
    }

    public function getCafeaLavazza(): ?int
    {
        return $this->CafeaLavazza;
    }

    public function setCafeaLavazza(?int $CafeaLavazza): static
    {
        $this->CafeaLavazza = $CafeaLavazza;

        return $this;
    }

    public function getZahar(): ?int
    {
        return $this->Zahar;
    }

    public function setZahar(?int $Zahar): static
    {
        $this->Zahar = $Zahar;

        return $this;
    }

    public function getLapte(): ?int
    {
        return $this->Lapte;
    }

    public function setLapte(?int $Lapte): static
    {
        $this->Lapte = $Lapte;

        return $this;
    }

    public function getCeai(): ?int
    {
        return $this->Ceai;
    }

    public function setCeai(?int $Ceai): static
    {
        $this->Ceai = $Ceai;

        return $this;
    }

    public function getSolubil(): ?int
    {
        return $this->Solubil;
    }

    public function setSolubil(?int $Solubil): static
    {
        $this->Solubil = $Solubil;

        return $this;
    }

    public function getPaharePlastic(): ?int
    {
        return $this->Pahare_plastic;
    }

    public function setPaharePlastic(?int $Pahare_plastic): static
    {
        $this->Pahare_plastic = $Pahare_plastic;

        return $this;
    }

    public function getPahareCarton(): ?int
    {
        return $this->Pahare_carton;
    }

    public function setPahareCarton(?int $Pahare_carton): static
    {
        $this->Pahare_carton = $Pahare_carton;

        return $this;
    }

    public function getPalete(): ?int
    {
        return $this->Palete;
    }

    public function setPalete(?int $Palete): static
    {
        $this->Palete = $Palete;

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
            $clientZiua->setClient($this);
        }

        return $this;
    }

    public function removeClientZiua(ClientZiua $clientZiua): static
    {
        if ($this->clientZiuas->removeElement($clientZiua)) {
            // set the owning side to null (unless already changed)
            if ($clientZiua->getClient() === $this) {
                $clientZiua->setClient(null);
            }
        }

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
