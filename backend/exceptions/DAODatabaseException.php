<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DAODatabaseException
 *
 * @author u61851
 */
class DAODatabaseException extends DAOException{
    public function __construct($message, $code) {
        parent::__construct('ADODb', $message, $code);
    }
    public function __toString() {
        return parent::__toString();
    }

}
?>
