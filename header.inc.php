<?php
/* CONSTANTES */
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}

define('VISTA', basename($_SERVER['PHP_SELF'], '.php'));
define('DAO_INTERFACE', ROOT_PATH . '/backend/dao/interface/');
define('SERVICE_INTERFACE', ROOT_PATH . '/backend/service/interface/');
define('SISTEMA', 'Peliculas 1.0');
define('JQUERY', 'jquery-1.11.1.min.js');

/* INCLUDES */
require_once(ROOT_PATH . '/backend/service/ServiceFactory.php');
require_once(ROOT_PATH . '/backend/service/CacheAPCService.php');
require_once(ROOT_PATH . '/backend/service/UtilsService.php');


/* AUTOLOAD
 *  Esta hecho para que cargue solo clases de las entidades.
 *  El resto se hace mediante los archivos de configuracion en /backend/config/
 *  configBD.ini, configDAO.ini y configService.ini
 * @param String $class_name
 */



function __autoload($class_name) {
    // busco en bussinessEntities las entidades del negocio
    if (file_exists(ROOT_PATH . '/backend/businessEntities/' . $class_name . '.php')) {
        require_once(ROOT_PATH . '/backend/businessEntities/' . $class_name . '.php');
    }
}

if (!isset($_SESSION)) {
    session_start();
}




?>
