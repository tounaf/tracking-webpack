<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 21/08/2019
 * Time: 11:02
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BasUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class FosUser
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\FosUserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity("email", message="Cet email est déjà utilisé")
 * @ORM\HasLifecycleCallbacks()
 */
class FosUser extends BasUser
{

    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="firstname",type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="lastname",type="string", nullable=true)
     */
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(name="phone_number",type="string",  nullable=true)
     */
    protected $phoneNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Societe", inversedBy="user")
     */
    private $societe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fonction")
     * @ORM\JoinColumn(name="fonction_id", referencedColumnName="id", nullable=true)
     */
    private $fonction;

    /**
     * @var bool
     * @ORM\Column(name="actif",type="boolean",  nullable=true)
     */
    private $actif = true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Profil")
     */
    private $profile;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $prefixPhone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dossier", mappedBy="userEnCharge")
     */
    private $dossiers;

    public function __construct()
    {
        parent::__construct();
        $this->dossiers = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return Societe|null
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * @param Societe $societe
     * @return FosUser
     */
    public function setSociete(Societe $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * @return Fonction
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * @param Fonction $fonction
     * @return FosUser
     */
    public function setFonction(Fonction $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setRoleUser()
    {
        if ($this->profile){
            $this->addRole(
                $this->profile?$this->profile->getCode():'');
        }
        
    }

    /**
     * @return bool
     */
    public function isActif(): bool
    {
        return $this->actif;
    }

    /**
     * @param bool $actif
     */
    public function setActif(bool $actif)
    {
        $this->actif = $actif;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setEnable()
    {
        $this->enabled = $this->actif;
    }

    public function getProfile(): ?Profil
    {
        return $this->profile;
    }

    public function setProfile(?Profil $profile): self
    {
        $this->profile = $profile;

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

    /**
     * @return Collection|Dossier[]|null
     */
    public function getDossiers()
    {
        return $this->dossiers;
    }

    public function addDossier(Dossier $dossier): self
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers[] = $dossier;
            $dossier->setUserEnCharge($this);
        }

        return $this;
    }

    public function removeRaisonSocial(Dossier $dossier): self
    {
        if ($this->dossiers->contains($dossier)) {
            $this->dossiers->removeElement($dossier);
            // set the owning side to null (unless already changed)
            if ($dossier->getUserEnCharge() === $this) {
                $dossier->setUserEnCharge(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}