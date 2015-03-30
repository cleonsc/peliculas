<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DAOConfigFileException
 *
 * @author u61851
 */
class DAOConfigFileException extends DAOException {
    public function __construct($dao, $message, $code) {
        parent::__construct($dao, $message, $code);

    }

    public function __toString() {
        return parent::__toString();
    }

}
?>
