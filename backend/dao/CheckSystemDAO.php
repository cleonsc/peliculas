<?php

/**
 * Description of CheckSystemDAO
 *
 * @author u57322
 */
//include_once(ROOT_PATH. '/backend/dao/GenericDAO.php');
//echo ROOT_PATH. '/backend/dao/GenericDAO.php';

class CheckSystemDAO extends DAOGeneric {

    function __construct($conexion) {
        $this->db = $conexion;
    }

    public function checkDbObjects($dbObjects, $type) {
        try {
            if ($type == 1) {
                $sql = "SELECT COUNT(1) AS CANT FROM " . $dbObjects . " WHERE ROWNUM < 10";
                $rs = $this->db->Execute($sql);
                $cantidad = $rs->GetRowAssoc(false);
                return $cantidad['cant'];
            } else {
                $sql = "SELECT COUNT(1) AS cant FROM USER_OBJECTS WHERE OBJECT_NAME = '" . $dbObjects . "' AND STATUS = 'VALID'";
                $rs = $this->db->Execute($sql);
                $result = $rs->GetRowAssoc(false);

                if ($result['cant'] >= '1') {
                    return 'ok';
                } else {
                    return 'error';
                }
            }
        } catch (Exception $e) {
            return 'error';
        }
    }

    public function checkConnection() {
        try {
            return is_resource($this->db->_connectionID);
        } catch (ADODB_Exception $adodb_exception) {
            return 'error';
        }
    }

    public function checkVersion() {
        try {
            $sql = "SELECT * FROM COMISIONES_VERSIONADO WHERE FECHA = (SELECT MAX(FECHA) FROM COMISIONES_VERSIONADO)";
            $rs = $this->db->Execute($sql);
            $version = $rs->GetRowAssoc(false);
            return $version;
        } catch (ADODB_Exception $adodb_exception) {
            return 'error';
        }
    }

    public function checkWebService() {
        try {
            // Levanto configuraciÃ³n
            $iniFile = $_SERVER['DOCUMENT_ROOT'] . '/backend/config/configWS.ini';

            if (!file_exists($iniFile)) {
                echo "No se encontrÃ³ el archivo de configuraciÃ³n de Web Services";
                exit;
            }

            $wsConfig = parse_ini_file($iniFile, true);

            // Testeo WS Cup
            return $this->testearWS();
        } catch (ADODB_Exception $adodb_exception) {
            return false;
        }
    }

    private function testearWS() {
        $agenteService = ServiceFactory::getService('agente');
        $cUsuario = 'U57322';
        //$cUsuario = 'U22103950';
        $agente = $agenteService->buscar($cUsuario);
        //echo '<pre>' . print_r($agente, true) . '</pre>';
        return $agente;
    }

    public function checkLog() {
        $file = 'log/logger.log';
        if (is_readable($file)) {
            return 'Ok';
        } else {
            return 'error';
        }
    }

}

?>
