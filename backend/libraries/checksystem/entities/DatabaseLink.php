<?php

namespace Checksystem;

require_once('DatabaseObject.php');

class DatabaseLink extends DatabaseObject {

	private $userName;
	private $host;
	private $linkOwner;
	private $fechaCreacion;

	public function __construct(DBConnection $conn, $dbLinkName, $configKey) {
		parent::__construct($conn, $dbLinkName, $configKey);
//		$this->objectType = 'DATABASE LINK';
	}

	public function getUserName() {
		return $this->userName;
	}

	public function getHost() {
		return $this->host;
	}

	public function checkExiste() {
		$sql = "SELECT owner, db_link, username, host, TO_CHAR(created, 'DD/MM/YYYY') AS created FROM all_db_links WHERE db_link = UPPER(:v_db_link_name) AND owner in ('PUBLIC', UPPER(:v_current_schema))";
		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, 'v_db_link_name', $this->objectName);
		oci_bind_by_name($stmt, 'v_current_schema', $this->owner);

		if (!@oci_execute($stmt)) {
			$e = oci_error();
			$this->setMensaje("Error al verificar la existencia del Database Link '{$this->objectName}' - {$e['message']}");
			$this->setEstado(false);
			return $this->getEstado();
		}

		$row = oci_fetch_array($stmt, OCI_ASSOC);
		$this->linkOwner = $row['OWNER'];
		$this->userName = $row['USERNAME'];
		$this->host = $row['HOST'];
		$this->fechaCreacion = $row['CREATED'];

		$this->setEstado(true);
		return $this->getEstado();
	}

	/**
	 * Verifica que el database link especificado sea accesible
	 * @return boolean
	 */
	public function checkAccesibilidad() {
		$sql = "select 1 from dual@{$this->objectName}";

		$stmt = oci_parse($this->getConnection(), $sql);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al intentar acceder al Database Link '{$this->objectName}' - {$e['message']}");
			return false;
		}

		return true;
	}

	public function chequear($detallado = false) {
		if ($this->checkExiste() && $this->checkAccesibilidad()) {
			$this->setEstado(true);
		} else {
			$this->setEstado(false);
		}
	}

}

?>