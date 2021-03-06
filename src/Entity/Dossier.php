<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Annotation\TrackableClass;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DossierRepository")
 * @ORM\HasLifecycleCallbacks()
 * @TrackableClass()
 */
class Dossier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referenceDossier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $sensLitige;

    /**
     * @ORM\Column(type="datetime")
     */
    private $echeance;

    /**
     * @ORM\Column(type="datetime", nullable=true)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\PartiAdverse")
     */
    private $partieAdverse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomPartieAdverse;

    /**
     * @ORM\Column(type="string")
     */
    private $statutPartiAdverse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StatutsPersMorale")
     */
    private $formePartieAdverse;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\InformationPj", inversedBy="dossiers", cascade={"persist"})
     */
    private $piecesJointes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise")
     */
    private $devise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SubDossier", mappedBy="dossier", cascade={"persist","remove","refresh"})
     */
    private $subDossiers;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auxiliaires", mappedBy="dossier")
     */
    private $auxiliaires;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Cloture", mappedBy="dossier")
     */
    private $cloture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervenant", mappedBy="dossier")
     */
    private $intervenants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="dossier")
     */
    private $histories;

    private $directory;

    private $pathUpload = '\public\pieces_jointes\litige';

    protected $fileName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PjDossier", mappedBy="dossier")
     */
    private $pjDossiers;

    private $ccmpt;
    public function __construct($_userCharge=null,$_societe=null)
    {
        $this->piecesJointes = new ArrayCollection();
        $this->subDossiers = new ArrayCollection();

        $this->auxiliaires = new ArrayCollection();
        $this->intervenants = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->pjDossiers = new ArrayCollection();
       if ($_userCharge) {
            $this->userEnCharge = $_userCharge;
        }
        if ($_societe) {
            $this->raisonSocial = $_societe;
        }

    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getReferenceDossier()
    {
        return $this->referenceDossier;
    }

    /**
     * @param $referenceDossier
     * @return Dossier
     */
    public function setReferenceDossier($referenceDossier): self
    {
        $ref = $referenceDossier+1;
        $this->referenceDossier = $this->raisonSocial ? $this->raisonSocial->getTrigramme() . '-' . str_pad((string)$ref, 4, '0', STR_PAD_LEFT) : str_pad($this->id, 4, '0', STR_PAD_LEFT);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param $libelle
     * @return Dossier
     */
    public function setLibelle($libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomDossier()
    {
        return $this->nomDossier;
    }

    /**
     * @param $nomDossier
     * @return Dossier
     */
    public function setNomDossier($nomDossier): self
    {
        $this->nomDossier = $nomDossier;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param $montant
     * @return Dossier
     */
    public function setMontant($montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSituation()
    {
        return $this->situation;
    }

    /**
     * @param $situation
     * @return Dossier
     */
    public function setSituation($situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResumeFait()
    {
        return $this->resumeFait;
    }


    /**
     * @param $resumeFait
     * @return Dossier
     */
    public function setResumeFait($resumeFait): self
    {
        $this->resumeFait = $resumeFait;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateLitige()
    {
        return $this->dateLitige;
    }

    /**
     * @param \DateTime $dateLitige
     * @return Dossier
     */
    public function setDateLitige(\DateTime $dateLitige=null): self
    {
        $this->dateLitige = $dateLitige;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSensLitige()
    {
        return $this->sensLitige;
    }

    /**
     * @param string $sensLitige
     * @return Dossier
     */
    public function setSensLitige(?string $sensLitige): self
    {
        $this->sensLitige = $sensLitige;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getEcheance()
    {
        return $this->echeance;
    }

    /**
     * @param \DateTime $echeance
     * @return Dossier
     */
    public function setEcheance(\DateTime $echeance=null): self
    {
        $this->echeance = $echeance;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getAlerteDate()
    {
        return $this->alerteDate;
    }

    /**
     * @param \DateTime $alerteDate
     * @return Dossier
     */
    public function setAlerteDate(\DateTime $alerteDate=null): self
    {
        $this->alerteDate = $alerteDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserEnCharge()
    {
        return $this->userEnCharge;
    }

    /**
     * @param FosUser|null $userEnCharge
     * @return Dossier
     */
    public function setUserEnCharge(?FosUser $userEnCharge): self
    {
        $this->userEnCharge = $userEnCharge;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRaisonSocial()
    {
        return $this->raisonSocial;
    }

    /**
     * @param Societe|null $raisonSocial
     * @return Dossier
     */
    public function setRaisonSocial(?Societe $raisonSocial): self
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param Statut|null $statut
     * @return Dossier
     */
    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param CategorieLitige|null $categorie
     * @return Dossier
     */
    public function setCategorie(?CategorieLitige $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEtapeSuivante()
    {
        return $this->etapeSuivante;
    }

    /**
     * @param EtapeSuivante|null $etapeSuivante
     * @return Dossier
     */
    public function setEtapeSuivante(?EtapeSuivante $etapeSuivante): self
    {
        $this->etapeSuivante = $etapeSuivante;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartieAdverse()
    {
        return $this->partieAdverse;
    }

    /**
     * @param PartiAdverse|null $partieAdverse
     * @return Dossier
     */
    public function setPartieAdverse(?PartiAdverse $partieAdverse): self
    {
        $this->partieAdverse = $partieAdverse;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomPartieAdverse()
    {
        return $this->nomPartieAdverse;
    }

    /**
     * @param string $nomPartieAdverse
     * @return Dossier
     */
    public function setNomPartieAdverse(string $nomPartieAdverse): self
    {
        $this->nomPartieAdverse = $nomPartieAdverse;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatutPartiAdverse()
    {
        return $this->statutPartiAdverse;
    }

    /**
     * @param string $statutPartiAdverse
     * @return Dossier
     */
    public function setStatutPartiAdverse(string $statutPartiAdverse): self
    {
        $this->statutPartiAdverse = $statutPartiAdverse;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormePartieAdverse()
    {
        return $this->formePartieAdverse;
    }

    /**
     * @param StatutsPersMorale|null $formePartieAdverse
     * @return Dossier
     */
    public function setFormePartieAdverse(?StatutsPersMorale $formePartieAdverse): self
    {
        $this->formePartieAdverse = $formePartieAdverse;

        return $this;
    }

/*    /**
     * @ORM\PrePersist()

    public function setNumerDosseier()
    {
       // $d = $this->count
        //dump($count);die();
        $aa = 0;
        if (empty($this->ccmpt)){
            $aa = 1;
        }
        else{
            $aa=$this->ccmpt+1;
        }
        $this->referenceDossier = $this->raisonSocial ? $this->raisonSocial->getTrigramme() . '-' . str_pad((string)$aa, 4, '0', STR_PAD_LEFT) : str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }*/

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

    /**
     * @return mixed
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * @param Devise|null $devise
     * @return Dossier
     */
    public function setDevise(?Devise $devise): self
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * @return Collection|SubDossier[]
     */
    public function getSubDossiers(): Collection
    {
        return $this->subDossiers;
    }

    public function addSubDossier(SubDossier $subDossier): self
    {
        if (!$this->subDossiers->contains($subDossier)) {
            $this->subDossiers[] = $subDossier;
            $subDossier->setDossier($this);
        }
        return $this;
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
            $auxiliaire->setDossier($this);
        }

        return $this;
    }

    public function removeAuxiliaire(Auxiliaires $auxiliaire): self
    {
        if ($this->auxiliaires->contains($auxiliaire)) {
            $this->auxiliaires->removeElement($auxiliaire);
            // set the owning side to null (unless already changed)
            if ($auxiliaire->getDossier() === $this) {
                $auxiliaire->setDossier(null);
            }
        }
        return $this;
    }

    public function removeSubDossier(SubDossier $subDossier): self
    {
        if ($this->subDossiers->contains($subDossier)) {
            $this->subDossiers->removeElement($subDossier);
            // set the owning side to null (unless already changed)
            if ($subDossier->getDossier() === $this) {
                $subDossier->setDossier(null);
            }
        }

        return $this;
    }


    /**
     * @return mixed
     */
    public function getCloture()
    {
        return $this->cloture;
    }


    public function __toString()
    {
        return (string)$this->referenceDossier;
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
            $intervenant->setDossier($this);
        }

        return $this;
    }

    public function removeIntervenant(Intervenant $intervenant): self
    {
        if ($this->intervenants->contains($intervenant)) {
            $this->intervenants->removeElement($intervenant);
            // set the owning side to null (unless already changed)
            if ($intervenant->getDossier() === $this) {
                $intervenant->setDossier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setDossier($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getDossier() === $this) {
                $history->setDossier(null);
            }
        }

        return $this;
    }

    public function setFileName($filename = null)
    {
        $this->fileName = $filename;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
            return;
        }

    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function getPathUpload()
    {
        return $this->pathUpload;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|PjDossier[]
     */
    public function getPjDossiers(): Collection
    {
        return $this->pjDossiers;
    }

    public function addPjDossier(PjDossier $pjDossier): self
    {
        if (!$this->pjDossiers->contains($pjDossier)) {
            $this->pjDossiers[] = $pjDossier;
            $pjDossier->setDossier($this);
        }

        return $this;
    }

}
