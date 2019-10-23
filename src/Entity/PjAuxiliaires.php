<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PjAuxiliairesRepository")
 */
class PjAuxiliaires
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dossier", inversedBy="pjAuxiliaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InformationPj", inversedBy="pjAuxiliaires")
     * @ORM\JoinColumn(nullable=true)
     */
    private $informationPj;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Auxiliaires", inversedBy="pjAuxiliaires")
     * @ORM\JoinColumn(nullable=true)
     */
    private $auxiliaire;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getInformationPj(): ?InformationPj
    {
        return $this->informationPj;
    }

    public function setInformationPj(?InformationPj $informationPj): self
    {
        $this->informationPj = $informationPj;

        return $this;
    }

    public function getAuxiliaire(): ?Auxiliaires
    {
        return $this->auxiliaire;
    }

    public function setAuxiliaire(?Auxiliaires $auxiliaire): self
    {
        $this->auxiliaire = $auxiliaire;

        return $this;
    }
}
