<?php

namespace App\Entity;

use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PjClotureRepository")
 * @ORM\HasLifecycleCallbacks()
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

    public function getCloture(): ?Cloture
    {
        return $this->cloture;
    }

    public function setCloture(?Cloture $cloture): self
    {
        $this->cloture = $cloture;

        return $this;
    }

    private $file;

    private $tempFilename;

    private $uploadDir;

    public function getFile()
    {
        return $this->file;
    }
    public function setFile(UploadedFile $file = null)
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
        //    return $this;

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
     */
    public function upload()
    {
        // If no file is set, do nothing
        if (null === $this->file)
        {
            return;
        }

        // A file is present, remove it
        if (null !== $this->name)
        {
            $oldFile = $this->getUploadRootDir().'/'.$this->name.'.'.$this->extension;

            if (file_exists($oldFile))
            {
                unlink($oldFile);
            }
        }
        // Move the file to the upload folder
        $this->file->move(
            $this->getUploadRootDir(),
            $this->name//.'.'.$this->extension
        );
    }
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // Save the name of the file we would want to remove
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->name;//.'.'.$this->extension;
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

    /**
     * @param FileUploader $fileUploader
     * @return mixed
     */
    public function getUploadDir(FileUploader $fileUploader)
    {
        // Upload directory
        return $this->uploadDir =  $fileUploader->getTargetDirectory();
    }

    /**
     * @return mixed
     */
    private function getUploadRootDir()
    {
        return $this->uploadDir;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->id.'.'.$this->extension;
    }
}
