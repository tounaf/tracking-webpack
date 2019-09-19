<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 16/09/2019
 * Time: 14:54
 */

namespace App\Entity;


class DossierSearch
{
    /**
     * @var string|null
     */
    private $reference;

    /**
     * @var
     */
    private $nom;

    /**
     * @var
     */
    private $categorie;

    /**
     * @var
     */
    private $entite;

    /**
     * @var
     */
    private $statut;

    /**
     * @return string|null
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string|null $reference
     * @return DossierSearch|null
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed|null $nom
     * @return DossierSearch|null
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param string $categorie
     * @return DossierSearch|null
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getEntite()
    {
        return $this->entite;
    }

    /**
     * @param string|null $entite
     * @return DossierSearch|null
     */
    public function setEntite($entite)
    {
        $this->entite = $entite;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param string|null $statut
     * @return DossierSearch|null
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
        return $this;
    }

}