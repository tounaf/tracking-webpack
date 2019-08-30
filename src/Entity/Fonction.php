<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FonctionRepository")
 */
class Fonction
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
    private $libele;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Profil")
     * @ORM\JoinColumn(name="profil_id", referencedColumnName="id")
     */
    private $profil;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable = true;

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
    public function getLibele()
    {
        return $this->libele;
    }

    /**
     * @param string $libele
     * @return Fonction
     */
    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * @param Profil $profil
     * @return Fonction
     */
    public function setProfil(Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     * @return Fonction
     *
     */
    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }
}
