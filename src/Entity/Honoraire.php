<?php

namespace App\Entity;

use App\Repository\HonoraireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HonoraireRepository::class)]
class Honoraire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $objet = null;

    #[ORM\Column]
    private ?float $montantht = null;

    #[ORM\Column(nullable: true)]
    private ?float $tva = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantttc = null;

    #[ORM\Column(nullable: true)]
    private ?float $rs = null;

    #[ORM\Column(nullable: true)]
    private ?float $timbrefiscal = null;

    #[ORM\Column(nullable: true)]
    private ?float $netapayer = null;

    #[ORM\ManyToOne(inversedBy: 'honoraires')]
    private ?Client $client = null;

    public function __construct()
    {
        // Automatically set the createdAt field to the current date and time
        $this->createdAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): static
    {
        $this->objet = $objet;

        return $this;
    }

    public function getMontantht(): ?float
    {
        return $this->montantht;
    }

    public function setMontantht(float $montantht): static
    {
        $this->montantht = $montantht;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(?float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getMontantttc(): ?float
    {
        return $this->montantttc;
    }

    public function setMontantttc(?float $montantttc): static
    {
        $this->montantttc = $montantttc;

        return $this;
    }

    public function getRs(): ?float
    {
        return $this->rs;
    }

    public function setRs(?float $rs): static
    {
        $this->rs = $rs;

        return $this;
    }

    public function getTimbrefiscal(): ?float
    {
        return $this->timbrefiscal;
    }

    public function setTimbrefiscal(?float $timbrefiscal): static
    {
        $this->timbrefiscal = $timbrefiscal;

        return $this;
    }

    public function getNetapayer(): ?float
    {
        return $this->netapayer;
    }

    public function setNetapayer(?float $netapayer): static
    {
        $this->netapayer = $netapayer;

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
