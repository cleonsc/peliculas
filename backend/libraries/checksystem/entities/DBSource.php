<?php

namespace Checksystem;

require_once('UserObject.php');

abstract class DBSource extends UserObject {

	protected $sourceSql;
	protected $remote;

	public function __construct(DBConnection $conn, $objectName, $configKey, $remote = false) {
		$this->remote = $remote;
		parent::__construct($conn, $objectName, $configKey);
	}

	public function getSql() {
		return $this->sourceSql;
	}

	/**
	 * Verifica la existencia de la funcion especificada en la tabla user_procedures
	 * @return boolean
	 */
	public function checkExiste() {
		$sql = "SELECT object_name, procedure_name FROM user_procedures WHERE object_name = UPPER(:v_function_name) AND object_type = :v_object_type";
		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, ":v_function_name", $this->objectName);
		oci_bind_by_name($stmt, ":v_object_type", $this->objectType);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al verificar la existencia del objeto {$this->objectType} '{$this->objectName}' - {$e['message']}");
			false;
		}

		$row = @oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS);

		if (empty($row)) {
			// Si el objeto se accede mediante un sinonimo, lo busca en all_objects
			if ($this->remote) {
				$sql2 = "SELECT object_name, procedure_name FROM all_procedures WHERE object_name = UPPER(:v_function_name) AND object_type = :v_object_type";
				$stmt2 = oci_parse($this->getConnection(), $sql2);
				oci_bind_by_name($stmt2, ":v_function_name", $this->objectName);
				oci_bind_by_name($stmt2, ":v_object_type", $this->objectType);

				if (!@oci_execute($stmt2)) {
					$e = oci_error($stmt2);
					$this->setMensaje("Error al verificar la existencia del objeto externo {$this->objectType} '{$this->objectName}' - {$e['message']}");
					false;
				}

				$row = @oci_fetch_array($stmt2, OCI_ASSOC | OCI_RETURN_NULLS);

				return true;
			} else {
				$this->setMensaje("No se encontr&oacute; el objeto {$this->objectType} '{$this->objectName}'");
				return false;
			}
		}

		return true;
	}

	/**
	 * Obtiene el SQL de la funcion especificada
	 * @return String or false
	 */
	protected function getObjectSql() {
		if ($this->remote) {
			$sql = "select line, text from all_source where name = UPPER(:v_function_name) and type = :v_object_type order by name, type, line";
		} else {
			$sql = "select line, text from user_source where name = UPPER(:v_function_name) and type = :v_object_type order by name, type, line";
		}

		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, ":v_function_name", $this->objectName);
		oci_bind_by_name($stmt, ":v_object_type", $this->objectType);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al obtener el SQL del objeto {$this->objectType} '{$this->objectName}' - {$e['message']}");
			return false;
		}

		$sqlResult = '';
		while ($row = oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS)) {
			$sqlResult .= $row['TEXT'];
		}

		$this->sourceSql = $sqlResult;

		if (empty($sqlResult)) {
			$this->setMensaje("No se pudo obtener el SQL del objeto {$this->objectType} '{$this->objectName}'");
			return false;
		}

		return $this->sourceSql;
	}

	public function chequear($detallado = false) {
		if ($this->checkExiste() && ($this->getUserObjectData() && $this->oracleStatus == 'VALID')) {
			if ($detallado) {
				$this->getObjectSql();
			}
			$this->setEstado(true);
		} else {
			$this->setEstado(false);
		}

		return $this->getEstado();
	}

}

?>