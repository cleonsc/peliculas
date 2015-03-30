<?php

namespace Checksystem;

require_once('Checkable.php');

abstract class DatabaseObject extends Checkable {
	
	/**
	 * @var DBConnection
	 */
	protected $dbConnection;
	protected $owner;

	public function __construct(DBConnection $conn, $objectName, $configKey) {
		$this->dbConnection = $conn;
		$this->owner = $conn->getUser();
		parent::__construct($objectName, $configKey);
	}

	/**
	 * 
	 * @return type
	 */
	protected function getConnection() {
		return $this->dbConnection->getConnection();
	}

	public function getOwner() {
		return $this->owner;
	}

	protected function setOwner($owner) {
		$this->owner = $owner;
	}
}

?>