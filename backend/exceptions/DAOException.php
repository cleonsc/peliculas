<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DAOException
 *
 * @author u61851
 */
class DAOException extends Exception {
    private $DAOClass;

    function  __construct($dao, $message, $code, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->DAOClass = $dao;
    }
    public function __toString() {
        return __CLASS__.' - '.$this->getDAOClass().": [ ".$this->getCode().' ] '.$this->getMessage();
    }
    public function getDAOClass(){
        return $this->DAOClass;
    }
}
?>
