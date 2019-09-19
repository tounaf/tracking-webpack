<?php

namespace App\Entity;

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
     * @ORM\Column(type="date",  nullable=true)
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
    private $devise;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Dossier", inversedBy="clotures")
     */
    private $dossier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->dateCloture;
    }

    public function setDateCloture(\DateTimeInterface $dateCloture): self
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

    public function setSensDecision(string $sensDecision): self
    {
        $this->sensDecision = $sensDecision;

        return $this;
    }

    public function getRisque(): ?string
    {
        return $this->risque;
    }

    public function setRisque(string $risque): self
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

    public function getDevise(): ?Devise
    {
        return $this->devise;
    }

    public function setDevise(?Devise $devise): self
    {
        $this->devise = $devise;

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

    public function setMontantIntervenant(int $montantIntervenant): self
    {
        $this->montantIntervenant = $montantIntervenant;

        return $this;
    }

    public function getMontantAuxiliaires(): ?int
    {
        return $this->montantAuxiliaires;
    }

    public function setMontantAuxiliaires(int $montantAuxiliaires): self
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
}
