<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfilRepository")
 */
class Profil
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
    private $libele;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $code;

    public function getId()
    {
        return $this->id;
    }

    public function getLibele()
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Profil constructor.
     */
    public function __construct()
    {
        return (string)$this->libele;
    }

    public function __toString()
    {
        return (string)$this->libele;
    }
}
