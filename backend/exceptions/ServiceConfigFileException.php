<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceConfigFileException
 *
 * @author u61851
 */
class ServiceConfigFileException extends ServiceException {
    public function __construct($service, $message, $code) {
        parent::__construct($service, $message, $code);
    }

    public function __toString() {
        return parent::__toString();
    }

}
?>
