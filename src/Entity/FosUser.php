<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 21/08/2019
 * Time: 11:02
 */

namespace App\Entity;


use FOS\UserBundle\Model\User as BasUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class FosUser
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\FosUserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity("email", message="Cet email est déjà utilisé")
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
    public function getSociete(): ?Societe
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

}