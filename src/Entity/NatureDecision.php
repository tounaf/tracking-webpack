<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NatureDecisionRepository")
 */
class NatureDecision
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActif=true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cloture", mappedBy="natureDecision")
     */
    private $clotures;

    public function __construct()
    {
        $this->clotures = new ArrayCollection();
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
     * @return Collection|Cloture[]
     */
    public function getClotures(): Collection
    {
        return $this->clotures;
    }

    public function addCloture(Cloture $cloture): self
    {
        if (!$this->clotures->contains($cloture)) {
            $this->clotures[] = $cloture;
            $cloture->setNatureDecision($this);
        }

        return $this;
    }

    public function removeCloture(Cloture $cloture): self
    {
        if ($this->clotures->contains($cloture)) {
            $this->clotures->removeElement($cloture);
            // set the owning side to null (unless already changed)
            if ($cloture->getNatureDecision() === $this) {
                $cloture->setNatureDecision(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->libelle;
    }
}
