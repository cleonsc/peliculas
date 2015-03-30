<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DAORequestedClassException
 *
 * @author u61851
 */
class DAORequestedClassException extends DAOException {
    public function __construct($dao, $message, $code) {
        if(empty($message)) $message = "La clase [ ".$dao.' ] NO existe.';
        
        parent::__construct($dao, $message, $code);
    }

    public function __toString() {
        return "La clase [ ".parent::getDAOClass().' ] NO existe.';
    }

}
?>
