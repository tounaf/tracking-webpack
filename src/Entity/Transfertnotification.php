<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransfertnotificationRepository")
 */
class Transfertnotification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FosUser", cascade={"persist"})
     */
    private $usernotif;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FosUser", cascade={"persist"})
     */
    private $usertransfer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datedebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datefin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsernotif(): ?FosUser
    {
        return $this->usernotif;
    }

    public function setUsernotif(FosUser $usernotif): self
    {
        $this->usernotif = $usernotif;

        return $this;
    }

    public function getUsertransfer(): ?FosUser
    {
        return $this->usertransfer;
    }

    public function setUsertransfer(FosUser $usertransfer): self
    {
        $this->usertransfer = $usertransfer;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }
}
