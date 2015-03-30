<?php

namespace Checksystem;

require_once('Table.php');

class View extends Table {

	private $viewSql;

	public function __construct(DBConnection $conn, $viewName, $configKey) {
		parent::__construct($conn, $viewName, $configKey);
		$this->objectType = 'VIEW';
		$this->viewSql = null;
	}

	public function checkExiste() {
		$sql = "SELECT count(*) AS existe FROM user_views WHERE view_name= UPPER(:v_view_name)";
		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, 'v_view_name', $this->objectName);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al verificar la existencia de la vista '{$this->objectName}' - {$e['message']}");
		}

		$row = oci_fetch_array($stmt, OCI_ASSOC);

		if (!$row['EXISTE']) {
			$this->setMensaje("No se pudo encontrar la vista '{$this->objectName}'");
			$this->estado = false;
		} else { // Si la tabla existe, verifica que sea accesible
			$this->estado = $this->checkAccesibilidad();
		}

		return $this->estado;
	}

	public function getSql() {
		return $this->viewSql;
	}

	public function getViewSql() {
		$sql = "SELECT text FROM user_views WHERE view_name = UPPER(:v_view_name)";
		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, 'v_view_name', $this->objectName);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al obtener el SQL de la vista {$this->objectName} - {$e['message']}");
			return false;
		}

		$row = oci_fetch_array($stmt, OCI_ASSOC);

		$this->viewSql = $row['TEXT'];
		return $this->viewSql;
	}

	public function chequear($detallado = false) {
		parent::chequear($detallado);
		if ($detallado) {
			$this->getViewSql();
		}
		
		return $this->getEstado();
	}
}

?>