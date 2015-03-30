<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ADODbOracleExecuteException
 *
 * @author u61851
 */
class ADODbOracleExecuteException extends ADODbException {
    
    public function __construct($message, $code) {
        parent::__construct($message, $code);

    }
    public function __toString() {
        return parent::__toString();
    }

}
?>
