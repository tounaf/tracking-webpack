<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PjClotureRepository")
 */
class PjCloture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InformationPj", inversedBy="pjClotures")
     */
    private $infoPj;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lienAccess;


    private $file;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tempFilename;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cloture", inversedBy="pjClotures")
     */
    private $cloture;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getInfoPj(): ?InformationPj
    {
        return $this->infoPj;
    }

    public function setInfoPj(?InformationPj $infoPj): self
    {
        $this->infoPj = $infoPj;

        return $this;
    }

    public function getLienAccess(): ?string
    {
        return $this->lienAccess;
    }

    public function setLienAccess(?string $lienAccess): self
    {
        $this->lienAccess = $lienAccess;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null): self
    {
        $this->file = $file;
        // Replacing a file ? Check if we already have a file for this entity
        if (null !== $this->extension)
        {
            // Save file extension so we can remove it later
            $this->tempFilename = $this->extension;

            // Reset values
            $this->extension = null;
            $this->name= null;
        }
        return $this;

    }

    public function getTempFilename(): ?string
    {
        return $this->tempFilename;
    }

    public function setTempFilename(?string $tempFilename): self
    {
        $this->tempFilename = $tempFilename;

        return $this;
    }


    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // If no file is set, do nothing
        if (null === $this->file)
        {
            return;
        }
        // The file name is the entity's ID
        // We also need to store the file extension
        $this->extension = $this->file->guessExtension();

        // And we keep the original name
        $this->name = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     * @ORM\PreUpdate()
     */
    public function upload()
    {
        // If no file is set, do nothing
        if (null === $this->file)
        {
            return;
        }

        // A file is present, remove it
        if (null !== $this->tempFilename)
        {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;

            if (file_exists($oldFile))
            {
                unlink($oldFile);
            }
        }

        // Move the file to the upload folder
        $this->file->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->extension
        );
    }
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // Save the name of the file we would want to remove
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // PostRemove => We no longer have the entity's ID => Use the name we saved
        if (file_exists($this->tempFilename))
        {
            // Remove file
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {
        // Upload directory
        return 'public/uploads';
        // This means /web/uploads/documents/
    }
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        // Image location (PHP)
        return __DIR__.$this->getUploadDir();
    }

    public function getUrl()
    {
        return $this->id.'.'.$this->extension;
    }

    public function getCloture(): ?Cloture
    {
        return $this->cloture;
    }

    public function setCloture(?Cloture $cloture): self
    {
        $this->cloture = $cloture;

        return $this;
    }

}
