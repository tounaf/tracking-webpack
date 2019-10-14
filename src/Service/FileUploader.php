<?php
namespace App\Service;
use http\Env\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function uploadSimpleFile($filename, $tmpName){


        return move_uploaded_file($tmpName, $this->getTargetDirectory().$filename);

    }

    public function upload(UploadedFile $file)
    {
        $fileName = $file->getClientOriginalName();
        try{
            if($this->checkfileUpload($fileName)){
                $file->move($this->getTargetDirectory(), $this->renamefileUpload($fileName));
                return $this->renamefileUpload($fileName);
            }else{
                return $file->move($this->getTargetDirectory(), $fileName);
            }

        }
        catch (FileException $e){
            return new \Symfony\Flex\Response('Upload failed !');
        }

    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function renamefileUpload($filename){
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $sansext = str_replace($ext,'', $filename);
        $sansext = str_replace('.','', $sansext);
        $filename = $sansext.date("Y-m-d-h-i-s").'.'.$ext;
        return $filename;
    }

    public function checkfileUpload($filename){
        if(in_array($filename, $this->listFileUpload()))
        {
            return true;
        }else
        {
            return false;
        }
    }
    public function listFileUpload(){
        $finder = new Finder();
        $finder->files()->in($this->getTargetDirectory());
        $listFichier = array();
        foreach($finder as $file){
            array_push($listFichier, $file->getFilename());
        }
        return $listFichier;
    }

    public function deleteFile($directoryFile, $fileName)
    {
        if(!empty($directoryFile))
        {
            $fs = new Filesystem();
            return $fs->remove($directoryFile.$fileName, $fileName);
        }
    }

    public function downFilePjIntervenant($filename)
    {

        if($this->checkfileUpload($filename))
        {
            $response = new \Symfony\Component\HttpFoundation\Response();
            $response->headers->set('Content-type', 'application/octet-stream');
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename ));
            $response->setContent(file_get_contents($this->targetDirectory.$filename));
            $response->setStatusCode(200);
            $response->headers->set('Content-Transfer-Encoding', 'binary');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            return $response;
        }else{
            return false;
        }

    }
}