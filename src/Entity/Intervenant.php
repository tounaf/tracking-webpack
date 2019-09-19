<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IntervenantRepository")
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
    private $devise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FosUser", inversedBy="intervenants")
     */
    private $user;

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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?FosUser
    {
        return $this->user;
    }

    public function setUser(?FosUser $user): self
    {
        $this->user = $user;

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
}
