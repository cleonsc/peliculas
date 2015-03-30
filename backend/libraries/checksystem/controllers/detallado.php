<?php

require_once('../checkSystem.inc.php');

$objectKey = $_REQUEST['key'];
$objectType = $_REQUEST['type'];

/* @var $checkSystem CheckSystemService */
$checkSystem = CheckSystemService::getInstance();
/* @TODO: reemplazar para que sólo obtenga la conexión necesaria */
$checkSystem->checkAllConnections();

switch ($objectType) {
	case 'table':
		/* @var $table Checksystem\Table */
		$table = $checkSystem->checkTable($objectKey, true);
		$aColumnas = $table->getColumnDetail();
		$vista = '/detailed_table.php';
		break;

	case 'view':
		/* @var $view Checksystem\View */
		$view = $checkSystem->checkView($objectKey, true);
		$aColumnas = $view->getColumnDetail();
		$aSql = explode("\n", $view->getSql());
		$vista = '/detailed_view.php';
		break;

	case 'trigger':
		/* @var $trigger Checksystem\Trigger */
		$trigger = $checkSystem->checkTrigger($objectKey, true);
		$vista = '/detailed_trigger.php';
		break;

	case 'package':
		/* @var $package Checksystem\Package */
		$package = $checkSystem->checkPackage($objectKey, true);
		$vista = '/detailed_package.php';
		break;

	case 'procedure':
		/* @var $procedure Checksystem\Procedure */
		$procedure = $checkSystem->checkProcedure($objectKey, true);
		$vista = '/detailed_procedure.php';
		break;

	case 'function':
		/* @var $function Checksystem\Function */
		$function = $checkSystem->checkFunction($objectKey, true);
		$vista = '/detailed_function.php';
		break;

	case 'job':
		/* @var $job Checksystem\Job */
		$job = $checkSystem->checkJob($objectKey, true);
		$vista = '/detailed_job.php';
		break;

	case 'sequence':
		/* @var $sequence Checksystem\Sequence */
		$sequence = $checkSystem->checkSequence($objectKey, true);
		$vista = '/detailed_sequence.php';
		break;

	case 'web_service':
		/* @var $webService Checksystem\WebService */
		$webService = $checkSystem->checkWebService($objectKey, true);
		$vista = '/detailed_web_service.php';
		break;
}

require_once(DIR_CHECKSYSTEM_VIEW . $vista);
?>