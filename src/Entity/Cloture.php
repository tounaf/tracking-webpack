<?php

namespace App\Entity;

use App\Annotation\TrackableClass;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClotureRepository")
 */
class Cloture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime",  nullable=true)
     */
    private $dateCloture;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $juridiction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DecisionCloture", inversedBy="clotures")
     */
    private $decisionLitige;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NiveauDecision", inversedBy="clotures")
     */
    private $niveauDecision;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NatureDecision", inversedBy="clotures")
     */
    private $natureDecision;

    /**
     * @ORM\Column(type="string", length=50,  nullable=true)
     */
    private $sensDecision;

    /**
     * @ORM\Column(type="string", length=50,  nullable=true)
     */
    private $risque;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $typeCloture;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $gainCondamnation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montantGainCondamn;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="clotures")
     */
    private $deviseGain;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montantInitial;

    /**
     * @ORM\Column(type="integer",  nullable=true)
     */
    private $montantIntervenant;

    /**
     * @ORM\Column(type="integer",  nullable=true)
     */
    private $montantAuxiliaires;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Dossier", inversedBy="cloture")
     */
    private $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="cloturesInitial")
     */
    private $deviseInitial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="cloturesAvocat")
     */
    private $deviseAvocat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="cloturesAuxi")
     */
    private $DeviseAuxiliaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PjCloture", mappedBy="cloture", cascade={"persist"})
     */
    private $pjClotures;

    public function __construct()
    {
        $this->pjClotures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateCloture()
    {
        return $this->dateCloture;
    }


    /**
     * @param \DateTime $dateCloture
     * @return Cloture
     */
    public function setDateCloture(\DateTimeInterface $dateCloture=null): self
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    public function getJuridiction(): ?string
    {
        return $this->juridiction;
    }

    public function setJuridiction(?string $juridiction): self
    {
        $this->juridiction = $juridiction;

        return $this;
    }

    public function getDecisionLitige(): ?DecisionCloture
    {
        return $this->decisionLitige;
    }

    public function setDecisionLitige(?DecisionCloture $decisionLitige): self
    {
        $this->decisionLitige = $decisionLitige;

        return $this;
    }

    public function getNiveauDecision(): ?NiveauDecision
    {
        return $this->niveauDecision;
    }

    public function setNiveauDecision(?NiveauDecision $niveauDecision): self
    {
        $this->niveauDecision = $niveauDecision;

        return $this;
    }

    public function getNatureDecision(): ?NatureDecision
    {
        return $this->natureDecision;
    }

    public function setNatureDecision(?NatureDecision $natureDecision): self
    {
        $this->natureDecision = $natureDecision;

        return $this;
    }

    public function getSensDecision(): ?string
    {
        return $this->sensDecision;
    }

    public function setSensDecision(?string $sensDecision): self
    {
        $this->sensDecision = $sensDecision;

        return $this;
    }

    public function getRisque(): ?string
    {
        return $this->risque;
    }

    public function setRisque(?string $risque): self
    {
        $this->risque = $risque;

        return $this;
    }

    public function getTypeCloture(): ?string
    {
        return $this->typeCloture;
    }

    public function setTypeCloture(?string $typeCloture): self
    {
        $this->typeCloture = $typeCloture;

        return $this;
    }

    public function getGainCondamnation(): ?string
    {
        return $this->gainCondamnation;
    }

    public function setGainCondamnation(?string $gainCondamnation): self
    {
        $this->gainCondamnation = $gainCondamnation;

        return $this;
    }

    public function getMontantGainCondamn(): ?int
    {
        return $this->montantGainCondamn;
    }

    public function setMontantGainCondamn(?int $montantGainCondamn): self
    {
        $this->montantGainCondamn = $montantGainCondamn;

        return $this;
    }

    public function getDeviseGain(): ?Devise
    {
        return $this->deviseGain;
    }

    public function setDeviseGain(?Devise $devise): self
    {
        $this->deviseGain = $devise;

        return $this;
    }

    public function getMontantInitial(): ?int
    {
        return $this->montantInitial;
    }

    public function setMontantInitial(?int $montantInitial): self
    {
        $this->montantInitial = $montantInitial;

        return $this;
    }

    public function getMontantIntervenant(): ?int
    {
        return $this->montantIntervenant;
    }

    public function setMontantIntervenant(?int $montantIntervenant): self
    {
        $this->montantIntervenant = $montantIntervenant;

        return $this;
    }

    public function getMontantAuxiliaires(): ?int
    {
        return $this->montantAuxiliaires;
    }

    public function setMontantAuxiliaires(?int $montantAuxiliaires): self
    {
        $this->montantAuxiliaires = $montantAuxiliaires;

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

    public function getDeviseInitial(): ?Devise
    {
        return $this->deviseInitial;
    }

    public function setDeviseInitial(?Devise $deviseInitial): self
    {
        $this->deviseInitial = $deviseInitial;

        return $this;
    }

    public function getDeviseAvocat(): ?Devise
    {
        return $this->deviseAvocat;
    }

    public function setDeviseAvocat(?Devise $deviseAvocat): self
    {
        $this->deviseAvocat = $deviseAvocat;

        return $this;
    }

    public function getDeviseAuxiliaires(): ?Devise
    {
        return $this->DeviseAuxiliaires;
    }

    public function setDeviseAuxiliaires(?Devise $DeviseAuxiliaires): self
    {
        $this->DeviseAuxiliaires = $DeviseAuxiliaires;

        return $this;
    }

    /**
     * @return Collection|PjCloture[]
     */
    public function getPjClotures(): Collection
    {
        return $this->pjClotures;
    }
    public function addPjCloture(PjCloture $pjCloture): self
    {
        if (!$this->pjClotures->contains($pjCloture)) {
            $this->pjClotures[] = $pjCloture;
            $pjCloture->setCloture($this);
        }

        return $this;
    }
    public function removePjCloture(PjCloture $pjCloture): self
    {
        if ($this->pjClotures->contains($pjCloture)) {
            $this->pjClotures->removeElement($pjCloture);
            // set the owning side to null (unless already changed)
            if ($pjCloture->getCloture() === $this) {
                $pjCloture->setCloture(null);
            }
        }

        return $this;
    }

    public function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        // Image location (PHP)
        return $this->getUploadDir();
    }

    public function getUploadDir()
    {
        // Upload directory
        return 'public/uploads';
        // This means /web/uploads/documents/
    }


    
}
