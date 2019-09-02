<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DecisionClotureRepository")
 */
class DecisionCloture
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
     * @return DecisionCloture
     */
    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     * @return DecisionCloture
     */
    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }
}
