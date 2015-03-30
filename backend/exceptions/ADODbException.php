<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ADODbException
 *
 * @author u61851
 */
class ADODbException extends Exception {
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return parent::__toString();
    }
}
?>
