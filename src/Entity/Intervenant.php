<?php

namespace App\Entity;

use App\Annotation\TrackableClass;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IntervenantRepository")
 * @TrackableClass()
 */
class Intervenant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="intervenants")
     */
    private $deviseConvInt;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePrestation", inversedBy="intervenants")
     */
    private $prestation;

    /**
     * @ORM\Column(type="integer")
     */
    private $convenu;

    /**
     * @ORM\Column(type="integer")
     */
    private $payer;

    /**
     * @ORM\Column(type="integer")
     */
    private $restePayer;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $statutIntervenant;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nomPrenom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dossier", inversedBy="intervenants")
     */
    private $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="devisePayer")
     */
    private $devisePayerInt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="deviseReste")
     */
    private $deviseResteInt;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $prefixPhone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PjIntervenant", mappedBy="intervenant")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pjIntervenants;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviseConvInt(): ?Devise
    {
        return $this->deviseConvInt;
    }

    public function setDeviseConvInt(?Devise $devise): self
    {
        $this->deviseConvInt = $devise;

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

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): self
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getDevisePayerInt(): ?Devise
    {
        return $this->devisePayerInt;
    }

    public function setDevisePayerInt(?Devise $devisePayer): self
    {
        $this->devisePayerInt = $devisePayer;

        return $this;
    }

    public function getDeviseResteInt(): ?Devise
    {
        return $this->deviseResteInt;
    }

    public function setDeviseResteInt(?Devise $deviseReste): self
    {
        $this->deviseResteInt = $deviseReste;

        return $this;
    }

    public function getPrefixPhone(): ?string
    {
        return $this->prefixPhone;
    }

    public function setPrefixPhone(string $prefixPhone): self
    {
        $this->prefixPhone = $prefixPhone;

        return $this;
    }

    public function getpjIntervenants(): ?PjIntervenant
    {
        return $this->pjIntervenants;
    }

    public function setpjIntervenants(?PjIntervenant $pjIntervenants): self
    {
        $this->pjIntervenants = $pjIntervenants;

        return $this;
    }
}
