<?php

/* @var $checkSystem CheckSystemService */
$checkSystem = CheckSystemService::getInstance();

if (!empty($global) && $global) {
	$estado = $checkSystem->checkAll(CheckSystemService::ND_ESTADO_GLOBAL);
	require_once(DIR_CHECKSYSTEM_VIEW . '/global.php');
} else {
	$aEstado = $checkSystem->checkAll(CheckSystemService::ND_ESTADO_GENERAL);
	$aModulosExternos = CheckSystemService::getModulosExternos();
	$aErrores = CheckSystemService::$aMensajes;
	require_once(DIR_CHECKSYSTEM_VIEW . '/general.php');
}

?>