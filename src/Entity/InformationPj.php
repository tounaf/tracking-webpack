<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InformationPjRepository")
 */
class InformationPj
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups("groupe1")
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActif=true;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Dossier", mappedBy="piecesJointes" , cascade={"persist"})
     */
    private $dossiers;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="litige", fileNameProperty="filename")
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PjCloture", mappedBy="infoPj")
     */
    private $pjClotures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PjDossier", mappedBy="informationPj")
     */
    private $pjDossiers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PjIntervenant", mappedBy="informationPj", orphanRemoval=true)
     */
    private $pjIntervenants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PjAuxiliaires", mappedBy="informationPj")
     */
    private $pjAuxiliaires;

    public function __construct()
    {
        $this->dossiers = new ArrayCollection();
        $this->pjClotures = new ArrayCollection();
        $this->pjDossiers = new ArrayCollection();
        $this->pjIntervenants = new ArrayCollection();
        $this->pjAuxiliaires = new ArrayCollection();
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
     * @return Collection|Dossier[]
     */
    public function getDossiers(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossier $dossier): self
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers[] = $dossier;
            $dossier->addPiecesJointe($this);
        }

        return $this;
    }

    public function removeDossier(Dossier $dossier): self
    {
        if ($this->dossiers->contains($dossier)) {
            $this->dossiers->removeElement($dossier);
            $dossier->removePiecesJointe($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     * @return InformationPj
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return InformationPj
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }



    public function setObjInfoPj($oInfPj){
        if($oInfPj instanceof InformationPj){

        }
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
            $pjDossier->setInformationPj($this);
        }

        return $this;
    }

    public function removePjDossier(PjDossier $pjDossier): self
    {
        if ($this->pjDossiers->contains($pjDossier)) {
            $this->pjDossiers->removeElement($pjDossier);
            // set the owning side to null (unless already changed)
            if ($pjDossier->getInformationPj() === $this) {
                $pjDossier->setInformationPj(null);
            }
        }

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
            $pjCloture->setInfoPj($this);
        }

        return $this;
    }

    public function removePjCloture(PjCloture $pjCloture): self
    {
        if ($this->pjClotures->contains($pjCloture)) {
            $this->pjClotures->removeElement($pjCloture);
            // set the owning side to null (unless already changed)
            if ($pjCloture->getInfoPj() === $this) {
                $pjCloture->setInfoPj(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getLibelle();
    }

    /**
     * @return Collection|PjIntervenant[]
     */
    public function getPjIntervenants(): Collection
    {
        return $this->pjIntervenants;
    }

    public function addPjIntervenant(PjIntervenant $pjIntervenant): self
    {
        if (!$this->pjIntervenants->contains($pjIntervenant)) {
            $this->pjIntervenants[] = $pjIntervenant;
            $pjIntervenant->setInformationPj($this);
        }

        return $this;
    }

    public function removePjIntervenant(PjIntervenant $pjIntervenant): self
    {
        if ($this->pjIntervenants->contains($pjIntervenant)) {
            $this->pjIntervenants->removeElement($pjIntervenant);
            // set the owning side to null (unless already changed)
            if ($pjIntervenant->getInformationPj() === $this) {
                $pjIntervenant->setInformationPj(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PjAuxiliaires[]
     */
    public function getPjAuxiliaires(): Collection
    {
        return $this->pjAuxiliaires;
    }

    public function addPjAuxiliaire(PjAuxiliaires $pjAuxiliaire): self
    {
        if (!$this->pjAuxiliaires->contains($pjAuxiliaire)) {
            $this->pjAuxiliaires[] = $pjAuxiliaire;
            $pjAuxiliaire->setInformationPj($this);
        }

        return $this;
    }

    public function removePjAuxiliaire(PjAuxiliaires $pjAuxiliaire): self
    {
        if ($this->pjAuxiliaires->contains($pjAuxiliaire)) {
            $this->pjAuxiliaires->removeElement($pjAuxiliaire);
            // set the owning side to null (unless already changed)
            if ($pjAuxiliaire->getInformationPj() === $this) {
                $pjAuxiliaire->setInformationPj(null);
            }
        }

        return $this;
    }

}
