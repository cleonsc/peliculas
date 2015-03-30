<?php

namespace Checksystem;

class Column {

	private $columnName; // COLUMN_NAME
	private $dataType;  // DATA_TYPE
	private $dataLength; // DATA_LENGTH
	private $isNullable; // NULLABLE
	private $defaultValue; // DATA_DEFAULT

	public function getColumnName() {
		return $this->columnName;
	}

	public function setColumnName($columnName) {
		$this->columnName = $columnName;
	}

	public function getDataType() {
		return $this->dataType;
	}

	public function setDataType($dataType) {
		$this->dataType = $dataType;
	}

	public function getDataLength() {
		return $this->dataLength;
	}

	public function setDataLength($dataLength) {
		$this->dataLength = $dataLength;
	}

	public function getIsNullable() {
		return $this->isNullable;
	}

	public function setIsNullable($isNullable) {
		$this->isNullable = $isNullable;
	}

	public function getDefaultValue() {
		return $this->defaultValue;
	}

	public function setDefaultValue($defaultValue) {
		$this->defaultValue = $defaultValue;
	}

}

?>