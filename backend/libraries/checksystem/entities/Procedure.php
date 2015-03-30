<?php

namespace Checksystem;

require_once('DBSource.php');

class Procedure extends DBSource {

	public function __construct(DBConnection $conn, $functionName, $configKey, $remote = false) {
		parent::__construct($conn, $functionName, $configKey, $remote);
		$this->objectType = 'PROCEDURE';
	}
}

?>