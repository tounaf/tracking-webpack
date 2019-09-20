<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 */
class History
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $classeName;

    /**
     * @ORM\Column(type="text")
     */
    private $metadata;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClasseName(): ?string
    {
        return $this->classeName;
    }

    public function setClasseName(?string $classeName): self
    {
        $this->classeName = $classeName;

        return $this;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(string $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

}
