<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceException
 *
 * @author u61851
 */

class ServiceException extends Exception{
    private $serviceClass;

    public function __construct($service, $message, $code) {
        $this->serviceClass = $service;
        parent::__construct($message, $code);
    }
    public function __toString() {
        return __CLASS__.' - '.$this->getServiceClass().": [ ".$this->getCode().' ] '.$this->getMessage();
    }
    public function getServiceClass(){
        return $this->serviceClass;
    }

}
?>
