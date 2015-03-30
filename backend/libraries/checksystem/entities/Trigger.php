<?php

namespace Checksystem;

require_once('UserObject.php');

class Trigger extends UserObject {

	private $triggerType;
	private $triggeringEvent;
	private $triggerStatus;
	private $description;
	private $triggerSql;
	private $affectedTable;

	public function __construct(DBConnection $conn, $triggerName, $configKey) {
		parent::__construct($conn, $triggerName, $configKey);
		$this->objectType = 'TRIGGER';
	}

	public function getDetalle() {
		$sql = "SELECT trigger_name, trigger_type, triggering_event, table_name, status, description, trigger_body FROM user_triggers WHERE trigger_name = UPPER(:v_trigger_name)";
		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, ":v_trigger_name", $this->objectName);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al obtener los datos del trigger '{$this->objectName}' de la tabla user_triggers - {$e['message']}");
			return false;
		}

		$row = oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS);

		if (empty($row)) {
			$this->setMensaje("No se pudo encontrar el trigger especificado en la tabla user_triggers");
			return false;
		}

		$this->triggerType = $row['TRIGGER_TYPE'];
		$this->triggeringEvent = $row['TRIGGERING_EVENT'];
		$this->affectedTable = $row['TABLE_NAME'];
		$this->triggerStatus = $row['STATUS'];
		$this->description = $row['DESCRIPTION'];
		$this->triggerSql = $row['TRIGGER_BODY'];

		if ($this->triggerStatus != 'ENABLED') {
			$this->setMensaje("El trigger se encuentra inhabilitado");
			return false;
		}

		return true;
	}

	public function getTriggerType() {
		return $this->triggerType;
	}

	public function getTriggeringEvent() {
		return $this->triggeringEvent;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getTriggerSql() {
		return $this->triggerSql;
	}

	public function getAffectedTable() {
		return $this->affectedTable;
	}

	public function chequear($detallado = false) {
		if (!$this->getUserObjectData()) {
			$this->setEstado(false);
		} else {
			$this->setEstado($this->getDetalle());
		}
		return $this->getEstado();
	}

}

?>