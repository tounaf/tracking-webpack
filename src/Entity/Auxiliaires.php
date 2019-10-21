<?php

namespace App\Entity;

use App\Annotation\TrackableClass;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuxiliairesRepository")
 * @TrackableClass()
 */
class Auxiliaires
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nomPrenom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=50,nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $telephone;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dossier", inversedBy="auxiliaires")
     */
    private $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="auxiliaires")
     */
    private $deviseAuxiConv;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePrestation", inversedBy="auxiliaires")
     */
    private $prestation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $convenu;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $payer;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $restePayer;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fonction;

    /**
     * @ORM\Column(type="string", length=50,nullable=true)
     */
    private $statutIntervenant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="devisePayeer")
     */
    private $deviseAuxiPayer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="DeviseResteAux")
     */
    private $deviseAuxiReste;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $prefixPhone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PjAuxiliaires", mappedBy="auxiliaire")
     */
    private $pjAuxiliaires;

    public function __construct()
    {
        $this->pjAuxiliaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDeviseAuxiConv(): ?Devise
    {
        return $this->deviseAuxiConv;
    }

    public function setDeviseAuxiConv(?Devise $devise): self
    {
        $this->deviseAuxiConv = $devise;

        return $this;
    }

    public function getPrestation(): ?TypePrestation
    {
        return $this->prestation;
    }

    public function setPrestation(?TypePrestation $prestation): self
    {
        $this->prestation = $prestation;

        return $this;
    }

    public function getConvenu(): ?int
    {
        return $this->convenu;
    }

    public function setConvenu(int $convenu): self
    {
        $this->convenu = $convenu;

        return $this;
    }

    public function getPayer(): ?int
    {
        return $this->payer;
    }

    public function setPayer(int $payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function getRestePayer(): ?int
    {
        return $this->restePayer;
    }

    public function setRestePayer(int $restePayer): self
    {
        $this->restePayer = $restePayer;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getStatutIntervenant(): ?string
    {
        return $this->statutIntervenant;
    }

    public function setStatutIntervenant(string $statutIntervenant): self
    {
        $this->statutIntervenant = $statutIntervenant;

        return $this;
    }

    public function getNomPrenom(): ?string
    {
        return $this->nomPrenom;
    }

    public function setNomPrenom(string $nomPrenom): self
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDeviseAuxiPayer(): ?Devise
    {
        return $this->deviseAuxiPayer;
    }

    public function setDeviseAuxiPayer(?Devise $deviseAuxiPayer): self
    {
        $this->deviseAuxiPayer = $deviseAuxiPayer;

        return $this;
    }

    public function getDeviseAuxiReste(): ?Devise
    {
        return $this->deviseAuxiReste;
    }

    public function setDeviseAuxiReste(?Devise $deviseReste): self
    {
        $this->deviseAuxiReste = $deviseReste;

        return $this;
    }

    public function getPrefixPhone(): ?string
    {
        return $this->prefixPhone;
    }

    public function setPrefixPhone(?string $prefixPhone): self
    {
        $this->prefixPhone = $prefixPhone;

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
            $pjAuxiliaire->setAuxiliaire($this);
        }

        return $this;
    }

    public function removePjAuxiliaire(PjAuxiliaires $pjAuxiliaire): self
    {
        if ($this->pjAuxiliaires->contains($pjAuxiliaire)) {
            $this->pjAuxiliaires->removeElement($pjAuxiliaire);
            // set the owning side to null (unless already changed)
            if ($pjAuxiliaire->getAuxiliaire() === $this) {
                $pjAuxiliaire->setAuxiliaire(null);
            }
        }

        return $this;
    }
}
