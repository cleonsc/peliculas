<?php

namespace Checksystem;

require_once('Checkable.php');

class WebService extends Checkable {

	private $url;
	private $wsdl;
	private $aMethods;
	private $aTypes;

	public function __construct($webServiceName, $configKey, $webServiceUrl) {
		$this->url = $webServiceUrl;
		parent::__construct($webServiceName, $configKey);
	}

	public function getUrl() {
		return $this->url;
	}

	public function getWsdl() {
		return $this->wsdl;
	}

	public function getMethods() {
		return $this->aMethods;
	}

	public function getTypes() {
		return $this->aTypes;
	}
	
	private function checkCURL() {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->getWSDLUrl());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		$response = curl_exec($ch);

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if($httpcode >= 200 && $httpcode < 300) {
			$this->setEstado(true);
		} else {
			$this->setMensaje("Respuesta HTTP {$httpcode}");
			$this->setEstado(false);
		}
		
		return $this->getEstado();
	}

	private function checkSoapClient() {
		try {
			$defaultTimeout = ini_get("default_socket_timeout");
			ini_set("default_socket_timeout", 15);

			$soapClient = new \SoapClient($this->getWSDLUrl());

			$this->aMethods = $soapClient->__getFunctions();
			$this->aTypes = $soapClient->__getTypes();

			$this->setEstado(true);
		} catch (SoapFault $f) {
			$mensajeError = '';
			$msg = $f->faultstring;
			
			if (strstr($msg, 'failed to load external entity')) {
				$mensajeError = 'Error de conexi&oacute;n - ';
			} else if ($f->faultcode == 'HTTP' && strstr(strtolower($f->faultstring), 'error fetching http headers')) {
				$mensajeError = 'Tiempo de espera superado - ';
			}

			$mensajeError .= $msg;
			$this->setEstado(false);
			$this->setMensaje($mensajeError);
		}

		ini_set("default_socket_timeout", $defaultTimeout);
		return $this->getEstado();
	}

	/**
	 * Arma la URL para el WSDL del servicio actual
	 * @return string
	 */
	private function getWSDLUrl() {
		if (strtolower(substr($this->url, strlen($this->url) - 5)) == '?wsdl') {
			$wsdlUrl = $this->url;
		} else {
			$wsdlUrl = $this->url . '?wsdl';
		}

		return $wsdlUrl;
	}

	public function getServiceWSDL() {
		$this->wsdl = file_get_contents($this->getWSDLUrl());
		
		return $this->wsdl;
	}

	public function chequear($detallado = false) {
		$this->checkCURL();
		$this->checkSoapClient();

		if ($detallado) {
			$this->getServiceWSDL();
		}
		
		return $this->getEstado();
	}

}

?>
