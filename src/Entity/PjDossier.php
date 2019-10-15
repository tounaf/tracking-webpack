<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PjDossierRepository")
 */
class PjDossier
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Dossier", inversedBy="pjDossiers")
     */
    private $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InformationPj", inversedBy="pjDossiers")
     */
    private $informationPj;

    private $infoPj;

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

    public function setFilename($filename){
        $this->filename = $filename;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
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

    public function getInfoPj(): ?InformationPj
    {
        return $this->infoPj;
    }

    public function setInfoPj(?InformationPj $infoPj): self
    {
        $this->infoPj = $infoPj;

        return $this;
    }
}
