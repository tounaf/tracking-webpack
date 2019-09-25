<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubDossierRepository")
 */
class SubDossier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("groupe1")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroSubDossier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dossier")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $dossier;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return SubDossier
     */
    public function setLibelle($libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumeroSubDossier()
    {
        return $this->numeroSubDossier;
    }

    /**
     * @param $numeroSubDossier
     * @return SubDossier
     */
    public function setNumeroSubDossier($numeroSubDossier): self
    {
        $this->numeroSubDossier = $numeroSubDossier;

        return $this;
    }

    /**
     * @return Dossier|null
     */
    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    /**
     * @param Dossier|null $dossier
     * @return SubDossier
     */
    public function setDossier(?Dossier $dossier): self
    {
        $this->dossier = $dossier;
        return $this;
    }
}
