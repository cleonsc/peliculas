<?php

namespace Checksystem;

class ForeignKey {

	private $objectName;
	private $tableName;
	private $columnName;
	private $referenceTableName;
	private $referenceColumnName;
	private $status;

	public function getObjectName() {
		return $this->objectName;
	}

	public function setObjectName($objectName) {
		$this->objectName = $objectName;
	}

	public function getTableName() {
		return $this->tableName;
	}

	public function setTableName($tableName) {
		$this->tableName = $tableName;
	}

	public function getColumnName() {
		return $this->columnName;
	}

	public function setColumnName($columnName) {
		$this->columnName = $columnName;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getReferenceTableName() {
		return $this->referenceTableName;
	}

	public function setReferenceTableName($referenceTableName) {
		$this->referenceTableName = $referenceTableName;
	}

	public function getReferenceColumnName() {
		return $this->referenceColumnName;
	}

	public function setReferenceColumnName($referenceColumnName) {
		$this->referenceColumnName = $referenceColumnName;
	}

}

?>