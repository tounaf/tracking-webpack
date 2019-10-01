<?php
namespace App\Service;
use http\Env\Response;
use Symfony\Component\Filesystem\Filesystem;
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
           $file->move($this->getTargetDirectory(), $fileName);
        }
        catch (FileException $e){
            return new Response('Upload failed !');
        }
        return;
    }

    public function getTargetDirectory(){
        return $this->targetDirectory;
    }

    public function deleteFile($directoryFile, $fileName){
        if(!empty($directoryFile)){
            $fs = new Filesystem();
            return $fs->remove($directoryFile, $fileName);
        }
    }
}