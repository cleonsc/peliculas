<?php

namespace Checksystem;

require_once('DBSource.php');

class PackageBody extends DBSource {

	public function __construct(DBConnection $conn, $packageName, $parentConfigKey, $remote = false) {
		parent::__construct($conn, $packageName, $parentConfigKey, $remote);
		$this->objectType = 'PACKAGE BODY';
	}
}

?>