<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DossierRepository")
 */
class Dossier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeroDossier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomDossier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montant;

    /**
     * @ORM\Column(type="string",columnDefinition="enum('demandeur','défendeur', 'tires')", nullable=true)
     */
    private $situation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resumeFait;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLitige;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('positif','négatif')")
     */
    private $sensLitige;

    /**
     * @ORM\Column(type="datetime")
     */
    private $echeance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $alerteDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FosUser", inversedBy="raisonSocial")
     */
    private $userEnCharge;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Societe")
     */
    private $raisonSocial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Statut")
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategorieLitige")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EtapeSuivante")
     */
    private $etapeSuivante;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\InformationPj")
     */
    private $piecesJointes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PartiAdverse")
     */
    private $partieAdverse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomPartieAdverse;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Personne physique','Personne morale')")
     */
    private $statutPartiAdverse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StatutsPersMorale")
     */
    private $formePartieAdverse;

    public function __construct()
    {
        $this->piecesJointes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroDossier()
    {
        return $this->numeroDossier;
    }

    public function setNumeroDossier($numeroDossier): self
    {
        $this->numeroDossier = $numeroDossier;

        return $this;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function setLibelle($libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNomDossier()
    {
        return $this->nomDossier;
    }

    public function setNomDossier($nomDossier): self
    {
        $this->nomDossier = $nomDossier;

        return $this;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant($montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getSituation()
    {
        return $this->situation;
    }

    public function setSituation($situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    public function getResumeFait()
    {
        return $this->resumeFait;
    }

    public function setResumeFait($resumeFait): self
    {
        $this->resumeFait = $resumeFait;

        return $this;
    }

    public function getDateLitige(): ?\DateTimeInterface
    {
        return $this->dateLitige;
    }

    public function setDateLitige(\DateTimeInterface $dateLitige): self
    {
        $this->dateLitige = $dateLitige;

        return $this;
    }

    public function getSensLitige()
    {
        return $this->sensLitige;
    }

    public function setSensLitige(string $sensLitige): self
    {
        $this->sensLitige = $sensLitige;

        return $this;
    }

    public function getEcheance(): ?\DateTimeInterface
    {
        return $this->echeance;
    }

    public function setEcheance(\DateTimeInterface $echeance): self
    {
        $this->echeance = $echeance;

        return $this;
    }

    public function getAlerteDate(): ?\DateTimeInterface
    {
        return $this->alerteDate;
    }

    public function setAlerteDate(\DateTimeInterface $alerteDate): self
    {
        $this->alerteDate = $alerteDate;

        return $this;
    }

    public function getUserEnCharge()
    {
        return $this->userEnCharge;
    }

    public function setUserEnCharge(?FosUser $userEnCharge): self
    {
        $this->userEnCharge = $userEnCharge;

        return $this;
    }

    public function getRaisonSocial()
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(?Societe $raisonSocial): self
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCategorie()
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieLitige $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getEtapeSuivante()
    {
        return $this->etapeSuivante;
    }

    public function setEtapeSuivante(?EtapeSuivante $etapeSuivante): self
    {
        $this->etapeSuivante = $etapeSuivante;

        return $this;
    }

    /**
     * @return Collection|InformationPj[]
     */
    public function getPiecesJointes(): Collection
    {
        return $this->piecesJointes;
    }

    public function addPiecesJointe(InformationPj $piecesJointe): self
    {
        if (!$this->piecesJointes->contains($piecesJointe)) {
            $this->piecesJointes[] = $piecesJointe;
        }

        return $this;
    }

    public function removePiecesJointe(InformationPj $piecesJointe): self
    {
        if ($this->piecesJointes->contains($piecesJointe)) {
            $this->piecesJointes->removeElement($piecesJointe);
        }

        return $this;
    }

    public function getPartieAdverse()
    {
        return $this->partieAdverse;
    }

    public function setPartieAdverse(?PartiAdverse $partieAdverse): self
    {
        $this->partieAdverse = $partieAdverse;

        return $this;
    }

    public function getNomPartieAdverse()
    {
        return $this->nomPartieAdverse;
    }

    public function setNomPartieAdverse(string $nomPartieAdverse): self
    {
        $this->nomPartieAdverse = $nomPartieAdverse;

        return $this;
    }

    public function getStatutPartiAdverse()
    {
        return $this->statutPartiAdverse;
    }

    public function setStatutPartiAdverse(string $statutPartiAdverse): self
    {
        $this->statutPartiAdverse = $statutPartiAdverse;

        return $this;
    }

    public function getFormePartieAdverse()
    {
        return $this->formePartieAdverse;
    }

    public function setFormePartieAdverse(?StatutsPersMorale $formePartieAdverse): self
    {
        $this->formePartieAdverse = $formePartieAdverse;

        return $this;
    }
}
