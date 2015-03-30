<?php

namespace Checksystem;

require_once('DBSource.php');
require_once('PackageBody.php');

class Package extends DBSource {

	/**
	 * @var PackageBody
	 */
	private $packageBody;

	public function __construct(DBConnection $conn, $packageName, $configKey, $remote = false) {
		parent::__construct($conn, $packageName, $configKey, $remote);
		$this->objectType = 'PACKAGE';
		$this->packageBody = new PackageBody($conn, $packageName, $configKey, $remote);
	}

	public function getPackageBody() {
		return $this->packageBody;
	}

	public function getPackageSql($getBody = true) {
		$aRespuesta = array();
		$aRespuesta['header'] = parent::getObjectSql();

		if ($getBody) {
			$aRespuesta['body'] = $this->packageBody->getObjectSql();
		}

		return $aRespuesta;
	}

	public function chequear($detallado = false) {
		$estado = parent::chequear();

		if ($estado && $detallado) {
			$this->getPackageSql();
		}

		return $this->getEstado();
	}

}

?>