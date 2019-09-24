<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuxiliairesRepository")
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
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephone;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dossier", inversedBy="auxiliaires")
     */
    private $dossier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="auxiliaires")
     */
    private $devise;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePrestation", inversedBy="auxiliaires")
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
    private $fonction;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $statutIntervenant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="devisePayeer")
     */
    private $deviseAuxiPayer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Devise", inversedBy="DeviseResteAux")
     */
    private $deviseReste;

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

    public function getDevise(): ?Devise
    {
        return $this->devise;
    }

    public function setDevise(?Devise $devise): self
    {
        $this->devise = $devise;

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

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
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

    public function getDeviseReste(): ?Devise
    {
        return $this->deviseReste;
    }

    public function setDeviseReste(?Devise $deviseReste): self
    {
        $this->deviseReste = $deviseReste;

        return $this;
    }
}
