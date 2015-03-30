<?php

namespace Checksystem;

require_once('Checkable.php');

class DBConnection extends Checkable {

	private $oConnection;
	private $dbString;
	private $dbUser;
	private $dbPassword;

	public function getUser() {
		return $this->dbUser;
	}

	public function getConnection() {
		return $this->oConnection;
	}

	public function __construct($dbString, $dbUser, $dbPassword, $configKey) {
		$this->oConnection = null;

		$this->dbString = $dbString;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;
		parent::__construct($dbUser, $configKey);
	}

	public function __destruct() {
		if (is_resource($this->oConnection)) {
			oci_close($this->oConnection);
		}
	}

	/**
	 * Conecta al esquema especificado
	 * @return boolean
	 */
	public function conectar() {
		// Supresión de errores para evitar warnings si falla el connect (No hay forma de frenarlos usando excepciones)
		$oConnection = @oci_connect($this->dbUser, $this->dbPassword, $this->dbString);

		if (is_resource($oConnection)) {
			$this->oConnection = $oConnection;
			return true;
		} else {
			$e = oci_error();
			$this->setMensaje($e['message']);
			return false;
		}
	}

	public function chequear($detallado = false) {
		if ($this->getEstado() == null) {
			$this->setEstado($this->conectar());
		}

		return $this->getEstado();
	}
}

?>