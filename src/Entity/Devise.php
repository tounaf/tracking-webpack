<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviseRepository")
 */
class Devise
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups("groupe1")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActif=true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervenant", mappedBy="Devise")
     */
    private $intervenants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auxiliaires", mappedBy="devise")
     */
    private $auxiliaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cloture", mappedBy="devise")
     */
    private $clotures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cloture", mappedBy="deviseInitial")
     */
    private $cloturesInitial;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cloture", mappedBy="deviseAvocat")
     */
    private $cloturesAvocat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cloture", mappedBy="cloturesAuxiliaires")
     */
    private $cloturesAuxiliaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cloture", mappedBy="DeviseAuxiliaires")
     */
    private $cloturesAuxi;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervenant", mappedBy="devisePayer")
     */
    private $devisePayer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervenant", mappedBy="deviseReste")
     */
    private $deviseReste;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auxiliaires", mappedBy="deviseAuxiPayer")
     */
    private $devisePayeer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auxiliaires", mappedBy="deviseReste")
     */
    private $DeviseResteAux;

    public function __construct()
    {
        $this->intervenants = new ArrayCollection();
        $this->auxiliaires = new ArrayCollection();
        $this->clotures = new ArrayCollection();
        $this->cloturesInitial = new ArrayCollection();
        $this->cloturesAvocat = new ArrayCollection();
        $this->cloturesAuxi = new ArrayCollection();
        $this->devisePayer = new ArrayCollection();
        $this->deviseReste = new ArrayCollection();
        $this->devisePayeer = new ArrayCollection();
        $this->DeviseResteAux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getIsActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): self
    {
        $this->isActif = $isActif;

        return $this;
    }

    /**
     * @return Collection|Intervenant[]
     */
    public function getIntervenants(): Collection
    {
        return $this->intervenants;
    }

    public function addIntervenant(Intervenant $intervenant): self
    {
        if (!$this->intervenants->contains($intervenant)) {
            $this->intervenants[] = $intervenant;
            $intervenant->setDevise($this);
        }

        return $this;
    }

    public function removeIntervenant(Intervenant $intervenant): self
    {
        if ($this->intervenants->contains($intervenant)) {
            $this->intervenants->removeElement($intervenant);
            // set the owning side to null (unless already changed)
            if ($intervenant->getDevise() === $this) {
                $intervenant->setDevise(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->code;
    }

    /**
     * @return Collection|Auxiliaires[]
     */
    public function getAuxiliaires(): Collection
    {
        return $this->auxiliaires;
    }

    public function addAuxiliaire(Auxiliaires $auxiliaire): self
    {
        if (!$this->auxiliaires->contains($auxiliaire)) {
            $this->auxiliaires[] = $auxiliaire;
            $auxiliaire->setDevise($this);
        }

        return $this;
    }

    public function removeAuxiliaire(Auxiliaires $auxiliaire): self
    {
        if ($this->auxiliaires->contains($auxiliaire)) {
            $this->auxiliaires->removeElement($auxiliaire);
            // set the owning side to null (unless already changed)
            if ($auxiliaire->getDevise() === $this) {
                $auxiliaire->setDevise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cloture[]
     */
    public function getClotures(): Collection
    {
        return $this->clotures;
    }

    public function addCloture(Cloture $cloture): self
    {
        if (!$this->clotures->contains($cloture)) {
            $this->clotures[] = $cloture;
            $cloture->setDevise($this);
        }

        return $this;
    }

    public function removeCloture(Cloture $cloture): self
    {
        if ($this->clotures->contains($cloture)) {
            $this->clotures->removeElement($cloture);
            // set the owning side to null (unless already changed)
            if ($cloture->getDevise() === $this) {
                $cloture->setDevise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cloture[]
     */
    public function getCloturesInitial(): Collection
    {
        return $this->cloturesInitial;
    }

    public function addCloturesInitial(Cloture $cloturesInitial): self
    {
        if (!$this->cloturesInitial->contains($cloturesInitial)) {
            $this->cloturesInitial[] = $cloturesInitial;
            $cloturesInitial->setDeviseInitial($this);
        }

        return $this;
    }

    public function removeCloturesInitial(Cloture $cloturesInitial): self
    {
        if ($this->cloturesInitial->contains($cloturesInitial)) {
            $this->cloturesInitial->removeElement($cloturesInitial);
            // set the owning side to null (unless already changed)
            if ($cloturesInitial->getDeviseInitial() === $this) {
                $cloturesInitial->setDeviseInitial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cloture[]
     */
    public function getCloturesAvocat(): Collection
    {
        return $this->cloturesAvocat;
    }

    public function addCloturesAvocat(Cloture $cloturesAvocat): self
    {
        if (!$this->cloturesAvocat->contains($cloturesAvocat)) {
            $this->cloturesAvocat[] = $cloturesAvocat;
            $cloturesAvocat->setDeviseAvocat($this);
        }

        return $this;
    }

    public function removeCloturesAvocat(Cloture $cloturesAvocat): self
    {
        if ($this->cloturesAvocat->contains($cloturesAvocat)) {
            $this->cloturesAvocat->removeElement($cloturesAvocat);
            // set the owning side to null (unless already changed)
            if ($cloturesAvocat->getDeviseAvocat() === $this) {
                $cloturesAvocat->setDeviseAvocat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cloture[]
     */
    public function getCloturesAuxi(): Collection
    {
        return $this->cloturesAuxi;
    }

    public function addCloturesAuxi(Cloture $cloturesAuxi): self
    {
        if (!$this->cloturesAuxi->contains($cloturesAuxi)) {
            $this->cloturesAuxi[] = $cloturesAuxi;
            $cloturesAuxi->setDeviseAuxiliaires($this);
        }

        return $this;
    }

    public function removeCloturesAuxi(Cloture $cloturesAuxi): self
    {
        if ($this->cloturesAuxi->contains($cloturesAuxi)) {
            $this->cloturesAuxi->removeElement($cloturesAuxi);
            // set the owning side to null (unless already changed)
            if ($cloturesAuxi->getDeviseAuxiliaires() === $this) {
                $cloturesAuxi->setDeviseAuxiliaires(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Intervenant[]
     */
    public function getDevisePayer(): Collection
    {
        return $this->devisePayer;
    }

    public function addDevisePayer(Intervenant $devisePayer): self
    {
        if (!$this->devisePayer->contains($devisePayer)) {
            $this->devisePayer[] = $devisePayer;
            $devisePayer->setDevisePayer($this);
        }

        return $this;
    }

    public function removeDevisePayer(Intervenant $devisePayer): self
    {
        if ($this->devisePayer->contains($devisePayer)) {
            $this->devisePayer->removeElement($devisePayer);
            // set the owning side to null (unless already changed)
            if ($devisePayer->getDevisePayer() === $this) {
                $devisePayer->setDevisePayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Intervenant[]
     */
    public function getDeviseReste(): Collection
    {
        return $this->deviseReste;
    }

    public function addDeviseReste(Intervenant $deviseReste): self
    {
        if (!$this->deviseReste->contains($deviseReste)) {
            $this->deviseReste[] = $deviseReste;
            $deviseReste->setDeviseReste($this);
        }

        return $this;
    }

    public function removeDeviseReste(Intervenant $deviseReste): self
    {
        if ($this->deviseReste->contains($deviseReste)) {
            $this->deviseReste->removeElement($deviseReste);
            // set the owning side to null (unless already changed)
            if ($deviseReste->getDeviseReste() === $this) {
                $deviseReste->setDeviseReste(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Auxiliaires[]
     */
    public function getDevisePayeer(): Collection
    {
        return $this->devisePayeer;
    }

    public function addDevisePayeer(Auxiliaires $devisePayeer): self
    {
        if (!$this->devisePayeer->contains($devisePayeer)) {
            $this->devisePayeer[] = $devisePayeer;
            $devisePayeer->setDeviseAuxiPayer($this);
        }

        return $this;
    }

    public function removeDevisePayeer(Auxiliaires $devisePayeer): self
    {
        if ($this->devisePayeer->contains($devisePayeer)) {
            $this->devisePayeer->removeElement($devisePayeer);
            // set the owning side to null (unless already changed)
            if ($devisePayeer->getDeviseAuxiPayer() === $this) {
                $devisePayeer->setDeviseAuxiPayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Auxiliaires[]
     */
    public function getDeviseResteAux(): Collection
    {
        return $this->DeviseResteAux;
    }

    public function addDeviseResteAux(Auxiliaires $deviseResteAux): self
    {
        if (!$this->DeviseResteAux->contains($deviseResteAux)) {
            $this->DeviseResteAux[] = $deviseResteAux;
            $deviseResteAux->setDeviseReste($this);
        }

        return $this;
    }

    public function removeDeviseResteAux(Auxiliaires $deviseResteAux): self
    {
        if ($this->DeviseResteAux->contains($deviseResteAux)) {
            $this->DeviseResteAux->removeElement($deviseResteAux);
            // set the owning side to null (unless already changed)
            if ($deviseResteAux->getDeviseReste() === $this) {
                $deviseResteAux->setDeviseReste(null);
            }
        }

        return $this;
    }

}
