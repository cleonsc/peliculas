<?php

namespace Checksystem;

require_once('UserObject.php');

class Sequence extends UserObject {

	private $minValue;
	private $maxValue;
	private $incrementBy;
	private $lastNumber;

	public function __construct(DBConnection $conn, $sequenceName, $configKey) {
		parent::__construct($conn, $sequenceName, $configKey);
		$this->objectType = 'SEQUENCE';
	}

	public function getMinValue() {
		return $this->minValue;
	}

	public function getMaxValue() {
		return $this->maxValue;
	}

	public function getIncrementBy() {
		return $this->incrementBy;
	}

	public function getLastNumber() {
		return $this->lastNumber;
	}

	public function getDetalleSecuencia() {
		$sql = 'select sequence_name, min_value, max_value, increment_by, last_number from user_sequences where sequence_name = UPPER(:v_sequence_name)';

		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, ":v_sequence_name", $this->objectName);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al obtener los datos del objeto '{$this->objectName}' de la tabla user_objects - {$e['message']}");
			$this->setEstado(false);
			return $this->getEstado();
		}

		$row = @oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS);

		if (!empty($row)) {
			$this->minValue = $row['MIN_VALUE'];
			$this->maxValue = $row['MAX_VALUE'];
			$this->incrementBy = $row['INCREMENT_BY'];
			$this->lastNumber = $row['LAST_NUMBER'];
		} else {
			$this->setMensaje("No se encontr&oacute; el objeto '{$this->objectName}' en la tabla user_objects");
			$this->setEstado(false);
			return $this->getEstado();
		}
	}

	public function chequear($detallado = false) {
		$this->setEstado($this->getUserObjectData());

		if ($this->getEstado() && $detallado) {
			$this->getDetalleSecuencia();
		}

		return $this->getEstado();
	}

}

?>