<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SocieteRepository")
 */
class Societe
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
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDropped = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FosUser", mappedBy="societe")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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
     * @return Societe
     */
    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

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
     * @return Societe
     */
    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsDropped()
    {
        return $this->isDropped;
    }

    /**
     * @param bool $isDropped
     * @return Societe
     */
    public function setIsDropped(bool $isDropped): self
    {
        $this->isDropped = $isDropped;

        return $this;
    }

    /**
     * @return Collection|FosUser[]
     */
    public function getUser(): Collection
    {
        return $this->users;
    }

    /**
     * @param FosUser $user
     * @return Societe
     */
    public function addUser(FosUser $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSociete($this);
        }

        return $this;
    }

    /**
     * @param FosUser $user
     * @return Societe
     */
    public function removeUser(FosUser $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getSociete() === $this) {
                $user->setSociete(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libele;
    }
}
