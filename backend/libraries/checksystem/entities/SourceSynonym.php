<?php

namespace Checksystem;

require_once('UserObject.php');
require_once('Synonym.php');
require_once('Package.php');
require_once('PackageBody.php');
require_once('DBFunction.php');
require_once('Procedure.php');

class SourceSynonym extends Synonym {

	protected $sourceType;

	/**
	 * @var DBSource
	 */
	protected $sourceObject;

	public function __construct(DBConnection $conn, $synonymName, $configKey, $sourceType) {
		$this->sourceType = $sourceType;
		parent::__construct($conn, $synonymName, $configKey);
	}

	public function getSourceType() {
		return $this->sourceType;
	}

	public function checkAccesibilidad() {
		
	}

	private function getSource() {
		$owner = $this->getTableOwner();
		$objectName = $this->getTableName();
		$remote = true;

		switch ($this->sourceType) {
			case "FUNCTION":
				$sourceObject = new DBFunction($this->dbConnection, $objectName, "{$objectName}@{$owner}", $remote);
				$sourceObject->getObjectSql();
				break;
			case "PROCEDURE":
				$sourceObject = new Procedure($this->dbConnection, $objectName, "{$objectName}@{$owner}", $remote);
				$sourceObject->getObjectSql();
				break;
			case "PACKAGE":
				$sourceObject = new Package($this->dbConnection, $objectName, "{$objectName}@{$owner}", $remote);
				$sourceObject->getPackageSql();
				break;
		}

		$this->sourceObject = $sourceObject;
	}

	public function getSourceObject() {
		return $this->sourceObject;
	}

	public function chequear($detallado = false) {
		$this->getDetalle();

		if ($detallado) {
			// Obtener fuentes del objeto de fuente especificado
			$this->getSource();
		}
	}

}

?>
