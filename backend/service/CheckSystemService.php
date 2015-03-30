<?php

/**
 * Description of CheckSystemService
 *
 * @author u62643
 */

class CheckSystemService {

    private $checksystemDAO;

    function __construct() {
        $this->checksystemDAO = DAOFactory::getDAO('checksystem');
    }

    public function checkDbObjects($dbObjects, $type) {
        return $this->checksystemDAO->checkDbObjects($dbObjects, $type);
    }

    public function checkConnection() {
        return $this->checksystemDAO->checkConnection();
    }

    public function checkVersion() {
        return $this->checksystemDAO->checkVersion();
    }

    public function checkWebService() {
        return $this->checksystemDAO->checkWebService();
    }

    public function checkMail($email) {
        $envioMailService = ServiceFactory::getService("enviomailservice");
        return $envioMailService->enviarMailCheckSystem($email);
    }

    public function checkLog() {
        return $this->checksystemDAO->checkLog();
    }

}

?>
