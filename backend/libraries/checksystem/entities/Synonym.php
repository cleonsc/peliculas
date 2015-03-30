<?php

namespace Checksystem;

require_once('UserObject.php');

class Synonym extends UserObject {

	private $tableOwner;
	private $tableName;
	private $dbLinkName;

	public function __construct(DBConnection $conn, $synonymName, $configKey) {
		parent::__construct($conn, $synonymName, $configKey);
		$this->objectType = 'SYNONYM';
	}

	public function getTableOwner() {
		return $this->tableOwner;
	}

	public function getTableName() {
		return $this->tableName;
	}

	public function getDbLinkName() {
		return $this->dbLinkName;
	}

	protected function getDetalle() {
		$sql = "SELECT synonym_name, table_owner, table_name, db_link FROM user_synonyms WHERE synonym_name = UPPER(:v_synonym_name)";

		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, ":v_synonym_name", $this->objectName);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al obtener los datos del sin&oacute;nimo '{$this->objectName}' de la tabla user_synonyms - {$e['message']}");
			$this->setEstado(false);
			return false;
		}

		$row = oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS);
		if (empty($row)) {
			$sqlPublic = "SELECT * FROM all_synonyms WHERE synonym_name = UPPER(:v_synonym_name) AND owner = 'PUBLIC'";

			$stmt2 = oci_parse($this->getConnection(), $sqlPublic);
			oci_bind_by_name($stmt2, ":v_synonym_name", $this->objectName);

			if (!@oci_execute($stmt2)) {
				$e = oci_error($stmt2);
				$this->setMensaje("Error al obtener los datos del sin&oacute;nimo '{$this->objectName}' de la tabla all_synonyms - {$e['message']}");
				$this->setEstado(false);
				return false;
			}

			$row = oci_fetch_array($stmt2, OCI_ASSOC | OCI_RETURN_NULLS);

			if (empty($row)) {
				$this->setMensaje("No se encontr&oacute; el sin&oacute;nimo '{$this->objectName}' en la tabla user_synonyms");
				$this->setEstado(false);
				return false;
			}
		}

		$this->tableOwner = $row['TABLE_OWNER'];
		$this->tableName = $row['TABLE_NAME'];
		$this->dbLinkName = $row['DB_LINK'];

		$this->setEstado(true);
		return true;
	}

	public function checkAccesibilidad() {
		$sql = "SELECT count(*) AS existe FROM {$this->objectName} WHERE 1 = 0";
		$stmt = oci_parse($this->getConnection(), $sql);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al acceder al sin&oacute;nimo '{$this->objectName}' - {$e['message']}");
			$this->setEstado(false);
			return false;
		}

		$this->setEstado(true);
		return true;
	}

	public function chequear($detallado = false) {
		$this->getDetalle();
		$this->checkAccesibilidad();

		return $this->getEstado();
	}

}

?>