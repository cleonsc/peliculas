<?php

namespace Checksystem;

require_once('DatabaseObject.php');

class Job extends DatabaseObject {

	private $jobId;
	private $lastDate;
	private $lastSec;
	private $nextDate;
	private $nextSec;
	private $interval;
	private $failures;
	private $jobSql;

	public function __construct(DBConnection $conn, $nombreProcedimiento, $configKey) {
		parent::__construct($conn, $nombreProcedimiento, $configKey);
	}

	public function getJobId() {
		return $this->jobId;
	}

	public function getLastDate() {
		return $this->lastDate;
	}

	public function getLastSec() {
		return $this->lastSec;
	}

	public function getNextDate() {
		return $this->nextDate;
	}

	public function getNextSec() {
		return $this->nextSec;
	}

	public function getInterval() {
		return $this->interval;
	}

	public function getFailures() {
		return $this->failures;
	}

	public function getJobSql() {
		return $this->jobSql;
	}

	public function buscarPorProcedimiento() {
		$sql = "SELECT job, to_char(last_date, 'DD/MM/YYYY') last_date, last_sec, to_char(next_date, 'DD/MM/YYYY') next_date, next_sec, interval, failures, what from user_jobs WHERE UPPER(what) LIKE UPPER('%' || :v_procedure_name || '%')";
		$stmt = oci_parse($this->getConnection(), $sql);
		oci_bind_by_name($stmt, ":v_procedure_name", $this->objectName);

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			$this->setMensaje("Error al obtener los datos del job que ejecuta el proceso '{$this->objectName}' de la tabla user_jobs - {$e['message']}");
			$this->setEstado(false);
		}

		$row = oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS);

		if (empty($row)) {
			$this->setEstado(false);
		}

		$this->jobId = $row['JOB'];
		$this->lastDate = $row['LAST_DATE'];
		$this->lastSec = $row['LAST_SEC'];
		$this->nextDate = $row['NEXT_DATE'];
		$this->nextSec = $row['NEXT_SEC'];
		$this->interval = $row['INTERVAL'];
		$this->failures = $row['FAILURES'];
		$this->jobSql = $row['WHAT'];

		$this->setEstado(true);
		return $this->getEstado();
	}

	public function getUserObjectData() {
		$this->setMensaje('Los jobs no poseen datos en la tabla user_objects');
		return false;
	}

	public function chequear($detallado = false) {
		$this->buscarPorProcedimiento();

		return $this->getEstado();
	}

}

?>