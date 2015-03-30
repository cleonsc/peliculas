<?php

namespace Checksystem;

require_once('UserObject.php');
require_once('Column.php');

class Table extends UserObject {

	protected $recordCount;
	protected $aColumns;

	public function __construct(DBConnection $conn, $tableName, $configKey) {
		parent::__construct($conn, $tableName, $configKey);
		$this->recordCount = -1;
		$this->objectType = 'TABLE';
	}

	/**
	 * Verifica la existencia de la tabla o vista actual en la tabla 'user_tables'
	 * @return type
	 */
	public function checkExiste() {
		$sql = "SELECT count(*) AS existe FROM user_tables WHERE table_name= UPPER(:v_table_name)";
		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, 'v_table_name', $this->objectName);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al verificar la existencia de la tabla '{$this->objectName}' - {$e['message']}");
			return false;
		}

		$row = oci_fetch_array($stmt, OCI_ASSOC);

		if (!$row['EXISTE']) {
			$this->setMensaje("No se pudo encontrar la tabla '{$this->objectName}'");
			return false;
		} else { // Si la tabla existe, verifica que sea accesible
			return true;
		}
	}

	/**
	 * Verifica si se encuentra accesible la tabla o vista actual
	 * @return boolean
	 */
	public function checkAccesibilidad() {
		$sql = "SELECT count(*) FROM {$this->objectName} WHERE 0 = 1";
		$stmt = oci_parse($this->getConnection(), $sql);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al buscar la tabla '{$this->objectName}' - {$e['message']}");
			$this->setEstado(false);
		} else {
			$this->setEstado(true);
		}

		return $this->getEstado();
	}

	/**
	 * Obtiene el detalle de columnas de la tabla o vista actual
	 * @return Array
	 */
	public function checkColumnDetail() {
		$sql = "SELECT column_name, data_type, data_length, nullable, data_default FROM user_tab_columns WHERE table_name = UPPER(:v_table)";
		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, ":v_table", $this->objectName);

		if (!@oci_execute($stmt)) {
			$e = oci_error();
			$this->setMensaje("Error al obtener el detalle de las columnas de la tabla o vista '{$this->objectName}' - {$e['message']}");
			$this->setEstado(false);
		}

		while ($row = oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS)) {
			$column = new Column();
			$column->setColumnName($row['COLUMN_NAME']);
			$column->setDataType($row['DATA_TYPE']);
			$column->setDataLength($row['DATA_LENGTH']);
			$column->setIsNullable($row['NULLABLE']);
			$column->setDefaultValue($row['DATA_DEFAULT']);
			$this->aColumns[] = $column;
		}

		return $this->aColumns;
	}

	/**
	 * Obtiene el número de registros que posee la tabla o vista actual
	 * @return type
	 */
	public function checkRecordCount() {
		// Inicializado en -1 para que no se vuelva a ejecutar si ya se tiene el valor
		if ($this->recordCount == -1) {
			$sql = "SELECT count(*) AS record_count FROM {$this->objectName}";
			$stmt = oci_parse($this->getConnection(), $sql);

			if (!@oci_execute($stmt)) {
				$e = oci_error();
				$this->setMensaje("Error al obtener la cantidad de registros de la tabla o vista '{$this->objectName}' - {$e['message']}");
				$this->setEstado(false);
			}

			$row = oci_fetch_array($stmt, OCI_ASSOC);
			$this->recordCount = $row['RECORD_COUNT'];
		}

		return $this->recordCount;
	}

	public function getRecordCount() {
		return $this->recordCount;
	}

	public function getColumnDetail() {
		return $this->aColumns;
	}

	
	public function chequear($detallado = false) {
		if ($this->checkExiste() && $this->checkAccesibilidad()) {
			if ($detallado) {
				$this->checkColumnDetail();
				$this->checkRecordCount();
			}
			$this->setEstado(true);
		} else {
			$this->setEstado(false);
		}

		return $this->getEstado();
	}

}

?>