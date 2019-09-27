<?php
namespace App\Service;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file){


        $fileName = $file->getClientOriginalName();
        try{
            return $file->move($this->getTargetDirectory(), $fileName);
        }
        catch (FileException $e){

        }
    }

    public function getTargetDirectory(){
        return $this->targetDirectory;
    }
}