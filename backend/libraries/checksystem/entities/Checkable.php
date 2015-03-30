<?php

namespace Checksystem;

abstract class Checkable {

	private $estado;
	private $mensaje;
	protected $objectName;
	protected $configKey;

	protected function __construct($objectName, $configKey) {
		$this->objectName = $objectName;
		$this->configKey = $configKey;
	}

	/**
	 * @param boolean $estado
	 */
	public function setEstado($estado) {
		$this->estado = $estado;
	}

	/**
	 * @return boolean
	 */
	public function getEstado() {
		return $this->estado;
	}

	/**
	 * @return string
	 */
	public function getObjectName() {
		return $this->objectName;
	}

	/**
	 * @param string $mensaje
	 */
	public function setMensaje($mensaje) {
		$this->mensaje = $mensaje;
	}

	/**
	 * @return string
	 */
	public function getMensaje() {
		return $this->mensaje;
	}

	public function getConfigKey() {
		return $this->configKey;
	}

	abstract public function chequear($detallado = false);
}

?>