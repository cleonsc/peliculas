<?php

namespace Checksystem;

class Constraint {

	private $constraintName;
	private $tableName;
	private $columnName;
	private $constraintType;
	private $status;

	public function getConstraintName() {
		return $this->constraintName;
	}

	public function setConstraintName($constraintName) {
		$this->constraintName = $constraintName;
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

	public function getConstraintType() {
		return $this->constraintType;
	}

	public function setConstraintType($constraintType) {
		$this->constraintType = $constraintType;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

}

?>