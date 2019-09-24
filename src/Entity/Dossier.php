<?php

namespace App\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DossierRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(type="string")
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
     * @ORM\ManyToMany(targetEntity="App\Entity\InformationPj", inversedBy="dossiers",cascade={"persist", "remove", "merge"} )
     */
    private $piecesJointes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise")
     */
    private $devise;

    protected $file;

    private $directory;

    private $pathUpload = '/public/pieces_jointes/litige';

    protected $fileName;

    public function __construct()
    {
        $this->piecesJointes = new ArrayCollection();
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
    public function getNumeroDossier()
    {
        return $this->numeroDossier;
    }

    /**
     * @param $numeroDossier
     * @return Dossier
     */
    public function setNumeroDossier($numeroDossier): self
    {
        $this->numeroDossier = $numeroDossier;

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
    public function setDateLitige(\DateTime $dateLitige): self
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
    public function setSensLitige(string $sensLitige): self
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
    public function setEcheance(\DateTime $echeance): self
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
    public function setAlerteDate(\DateTime $alerteDate): self
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

    /**
     * @ORM\PrePersist()
     */
    public function setNumerDosseier()
    {
        $this->numeroDossier = $this->nomDossier.'-'.$this->id;
        $this->libelle = $this->nomDossier;

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

    public function getFile(){
        return $this->file;
    }

    public function setFile(UploadedFile $file = null){
        $this->file = $file;
    }

    public function setFileName($filename = null){
        $this->fileName = $filename;
    }

    public function getFileName(){
        return $this->fileName;
    }

    public function upload(){
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
            return;
        }

    }

    public function setDirectory($directory){
        $this->directory = $directory;
    }

    public function getDirectory(){
        return $this->directory;
    }

    public function getPathUpload(){
        return $this->pathUpload;
    }

    public function UpdateObjDossier($dataDossierUpdate, $dossier){
        if(is_object($dataDossierUpdate)){
            $dataDossierUpdate->setNumeroDossier($dossier->getNumeroDossier());
            $dataDossierUpdate->setLibelle($dossier->getLibelle());
            $dataDossierUpdate->setNomDossier($dossier->getNomDossier());
            $dataDossierUpdate->setStatut($dossier->getStatut());
            $dataDossierUpdate->setMontant($dossier->getMontant());
            $dataDossierUpdate->setSituation($dossier->getSituation());
            $dataDossierUpdate->setResumeFait($dossier->getResumeFait());
            $dataDossierUpdate->setDateLitige($dossier->getDateLitige());
            $dataDossierUpdate->setSensLitige($dossier->getSensLitige());
            $dataDossierUpdate->setEcheance($dossier->getEcheance());
            $dataDossierUpdate->setAlerteDate($dossier->getAlerteDate());
            $dataDossierUpdate->setRaisonSocial($dossier->getRaisonSocial());
            $dataDossierUpdate->setCategorie($dossier->getCategorie());
            $dataDossierUpdate->setEtapeSuivante($dossier->getEtapeSuivante());
            $dataDossierUpdate->setPartieAdverse($dossier->getPartieAdverse());
            $dataDossierUpdate->setNomPartieAdverse($dossier->getNomPartieAdverse());
            $dataDossierUpdate->setStatutPartiAdverse($dossier->getStatutPartiAdverse());
            $dataDossierUpdate->setFormePartieAdverse($dossier->getFormePartieAdverse());
            $dataDossierUpdate->setDevise($dossier->getDevise());
            $dataDossierUpdate->setFileName($dossier->getFileName());
            return $dataDossierUpdate;
        }
    }

    }
