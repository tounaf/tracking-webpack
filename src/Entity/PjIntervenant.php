<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PjIntervenantRepository")
 */
class PjIntervenant
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dossier", inversedBy="pjIntervenants")
     * @ORM\JoinColumn(nullable=true)
     */
    private $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InformationPj", inversedBy="pjIntervenants")
     * @ORM\JoinColumn(nullable=true)
     */
    private $informationPj;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Intervenant", inversedBy="pjIntervenants", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $intervenant;

    private $file;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->intervenant = new ArrayCollection();
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


    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }

    public function setIntervenant(?Intervenant $intervenant): self {
        $this->intervenant = $intervenant;
        return $this;
    }

    public function addIntervenant(Intervenant $intervenant): self
    {
        if (!$this->intervenant->contains($intervenant)) {
            $this->intervenant[] = $intervenant;
            $intervenant->setIntervenant($this);
        }

        return $this;
    }

    public function removeIntervenant(Intervenant $intervenant): self
    {
        if ($this->intervenant->contains($intervenant)) {
            $this->intervenant->removeElement($intervenant);
            // set the owning side to null (unless already changed)
            if ($intervenant->getIntervenant() === $this) {
                $intervenant->setIntervenant(null);
            }
        }

        return $this;
    }

    public function setFile($file){
        $this->file = $file;
    }

    public function  getFile(){
        return $this->file;
    }
}
