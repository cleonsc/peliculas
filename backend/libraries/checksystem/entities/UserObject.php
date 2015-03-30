<?php

namespace Checksystem;

require_once('DatabaseObject.php');

abstract class UserObject extends DatabaseObject {

	protected $fechaCreacion;
	protected $fechaModificacion;
	protected $oracleStatus;
	protected $objectType;

	public function __construct(DBConnection $conn, $objectName, $configKey) {
		parent::__construct($conn, $objectName, $configKey);
		$this->objectType = 'SIN TIPO';
	}

	public function getObjectName() {
		return $this->objectName;
	}

	public function setObjectName($objectName) {
		$this->objectName = $objectName;
	}

	public function getFechaCreacion() {
		return $this->fechaCreacion;
	}

	public function setFechaCreacion($fechaCreacion) {
		$this->fechaCreacion = $fechaCreacion;
	}

	public function getFechaModificacion() {
		return $this->fechaModificacion;
	}

	public function setFechaModificacion($fechaModificacion) {
		$this->fechaModificacion = $fechaModificacion;
	}

	public function getOracleStatus() {
		return $this->oracleStatus;
	}

	public function setOracleStatus($oracleStatus) {
		$this->oracleStatus = $oracleStatus;
	}

	public function getUserObjectData() {
		$sql = "SELECT object_name, object_type, TO_CHAR(created, 'DD/MM/YYYY') AS created, TO_CHAR(last_ddl_time, 'DD/MM/YYYY') AS last_ddl_time, status FROM user_objects WHERE object_name = UPPER(:v_obj_name) AND object_type = UPPER(:v_obj_type)";

		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, ":v_obj_name", $this->objectName);
		oci_bind_by_name($stmt, ":v_obj_type", $this->objectType);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al obtener los datos del objeto '$this->objectName' de la tabla user_objects - {$e['message']}");
			return false;
		}

		$row = @oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS);

		if (empty($row)) {
			$this->setMensaje("No se pudo encontrar el objeto '$this->objectName' en la tabla user_objects");
			return false;
		}

		$this->fechaCreacion = $row['CREATED'];
		$this->fechaModificacion = $row['LAST_DDL_TIME'];
		$this->oracleStatus = $row['STATUS'];

		if ($this->oracleStatus != 'VALID') {
			$this->setMensaje("El objeto '$this->objectName' tiene el estado '{$this->oracleStatus}' en la tabla user_objects");
			return false;
		}

		return true;
	}

}

?>