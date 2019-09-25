<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypePrestationRepository")
 */
class TypePrestation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("groupe1")
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActif=true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervenant", mappedBy="Prestation")
     */
    private $intervenants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auxiliaires", mappedBy="prestation")
     */
    private $auxiliaires;

    public function __construct()
    {
        $this->intervenants = new ArrayCollection();
        $this->auxiliaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getIsActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): self
    {
        $this->isActif = $isActif;

        return $this;
    }

    /**
     * @return Collection|Intervenant[]
     */
    public function getIntervenants(): Collection
    {
        return $this->intervenants;
    }

    public function addIntervenant(Intervenant $intervenant): self
    {
        if (!$this->intervenants->contains($intervenant)) {
            $this->intervenants[] = $intervenant;
            $intervenant->setPrestation($this);
        }

        return $this;
    }

    public function removeIntervenant(Intervenant $intervenant): self
    {
        if ($this->intervenants->contains($intervenant)) {
            $this->intervenants->removeElement($intervenant);
            // set the owning side to null (unless already changed)
            if ($intervenant->getPrestation() === $this) {
                $intervenant->setPrestation(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->libelle;
    }

    /**
     * @return Collection|Auxiliaires[]
     */
    public function getAuxiliaires(): Collection
    {
        return $this->auxiliaires;
    }

    public function addAuxiliaire(Auxiliaires $auxiliaire): self
    {
        if (!$this->auxiliaires->contains($auxiliaire)) {
            $this->auxiliaires[] = $auxiliaire;
            $auxiliaire->setPrestation($this);
        }

        return $this;
    }

    public function removeAuxiliaire(Auxiliaires $auxiliaire): self
    {
        if ($this->auxiliaires->contains($auxiliaire)) {
            $this->auxiliaires->removeElement($auxiliaire);
            // set the owning side to null (unless already changed)
            if ($auxiliaire->getPrestation() === $this) {
                $auxiliaire->setPrestation(null);
            }
        }

        return $this;
    }
}
