<?php
if (!defined('DIR_CHECKSYSTEM_ROOT')) {
	define('DIR_CHECKSYSTEM_ROOT', __DIR__ . '/');
}

if (!defined('DIR_CHECKSYSTEM_VIEW')) {
	define('DIR_CHECKSYSTEM_VIEW', DIR_CHECKSYSTEM_ROOT . 'views/');
}

if (!defined('DIR_CHECKSYSTEM_CONTROLLER')) {
	define('DIR_CHECKSYSTEM_CONTROLLER', DIR_CHECKSYSTEM_ROOT . 'controllers/');
}

if (!defined('DIR_CHECKSYSTEM_CONFIG')) {
	define('DIR_CHECKSYSTEM_CONFIG', DIR_CHECKSYSTEM_ROOT . '../../config/');
}

if (!defined('ROOT_PATH')) {
	define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

if (!defined('CHECKSYSTEM_VERSION')) {
	define('CHECKSYSTEM_VERSION', '2.1.2');
}

require_once(DIR_CHECKSYSTEM_ROOT . '/CheckSystemService.php');

?>