<?php

namespace App\Entity;

use App\Repository\ClientZiuaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientZiuaRepository::class)]
class ClientZiua
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'clientZiuas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Data $data = null;

    #[ORM\ManyToOne(inversedBy: 'clientZiuas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?Data
    {
        return $this->data;
    }

    public function setData(?Data $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
