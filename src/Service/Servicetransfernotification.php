<?php
/**
 * Created by PhpStorm.
 * User: ambinintsoa
 * Date: 18/10/2019
 * Time: 09:25
 */
namespace App\Service;
class Servicetransfernotification{
    protected  $request;
    protected  $dataTransfer;
    public  function __construct(){

    }

    public function setRequestData($requestdata){
        $this->request = $requestdata;
    }

    public function setDataTransfer($datatransfer){
        $this->dataTransfer = $datatransfer;
    }

    public function getRequestData(){
        return $this->request;
    }

    public function getDataTransfer(){
        return $this->dataTransfer();
    }
}