<?php
/*****************************************************************
det_nav_class v1.4
ULTIMA ACTUALIZACION 02/12/2013, se agrega detección de Internet Explorer 11 (windows 7 y windows 8), el nombre clave para la detección es IE11
Clase para detección de Navegadores y Paltaforma Operativa
Guillermo Gianello(c)2009
Comentarios y sugerencias a gc.gianello@gmail.com

Como usar la clase det_nav_class.php
En su php inserte el siguiente bloque

<?php
################## inclusión det_nav_class ########################
require('det_nav_class.php'); //determinar ruta a det_nav_class.php
$nav = new detNavegador();
$navegador = ($nav->miNavegador());
$nombre_navegador = $navegador['nav_tipo'];
$version_navegador = $navegador['version'];
$sistema_operativo = $navegador['sistema_op'];
###################################################################
?>

Una vez hecho esto ya dispondrá de las variables necesarias
para usar en sus condicionales, por ejemlo

<?php
################## condicionales det_nav_class ####################
if ( $nombre_navegador == 'MSIE' ){
if ($version_navegador < 7){
echo "debe actualizar a IE7 o superior";
}else{
echo "su versión de IE está actualizada";
}
}
###################################################################
?>

Lista de Nombres posibles de Navegadores reconocidos:

Opera
Firefox
Konqueror
MSIE
IE11
MSPIE
Icab (Mac)
OmniWeb (Mac)
Lynx
Safari
Chrome
W3css

@actualización 12-5-2010 (Agregado detección IE 64x)
@actualización 15-8-2011 (reemplazado eregi() por preg_match()
********************************************************************/
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

class detNavegador{
	var $nombre_nav = NULL;
	var $version = NULL;
	var $bits = NULL;
	var $useragent = NULL;
	var $sistema_op;
	var $aol = FALSE;
	var $nav_tipo;
	function detNavegador(){
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$this->useragent = $agent;
	}

	function det_sistemaOperativo(){
	$win = preg_match("/win/i", $this->useragent);
	$linux = preg_match("/linux/i", $this->useragent);
	$mac = preg_match("/mac/i", $this->useragent);
	$os2 = preg_match("/OS\/2/i", $this->useragent);
	$beos = preg_match("/BeOS/i", $this->useragent);
	
	if($win){
	$this->sistema_op = "Windows";
	}elseif ($linux){
	$this->sistema_op = "Linux"; 
	}elseif ($mac){
	$this->sistema_op = "Macintosh"; 
	}elseif ($os2){
	$this->sistema_op = "OS/2"; 
	}elseif ($beos){
	$this->sistema_op = "BeOS"; 
	}
	return $this->sistema_op;
	}
	
### Opera

	function EsOpera(){		
	if (preg_match("/opera/i",$this->useragent)){
	$valor = stristr($this->useragent, "opera");
	$separar = "/\//";
	if(preg_match($separar, $valor)){
	$valor = explode("/",$valor);
	$this->nav_tipo = $valor[0];
	$valor = explode(" ",$valor[1]);

	$this->version = $valor[0];
 if (preg_match("/Version\//", $_SERVER['HTTP_USER_AGENT'])){
 $valor = explode("Version/", $_SERVER['HTTP_USER_AGENT']);
 $this->version = $valor[1];
 }

	}else{
	$valor = explode(" ",stristr($valor,"opera"));
	$this->nav_tipo = $valor[0];
	$this->version = $valor[1];
	}
	
 return TRUE;
	}else{
	return FALSE;
	}
	
}

### Firefox
	
	function EsFirefox(){
	if(preg_match("/Firefox/i", $this->useragent)){
	$this->nav_tipo = "Firefox"; 
	$valor = stristr($this->useragent, "Firefox");
	$valor = explode("/",$valor);
 $valor = explode(" ",$valor[1]);
	$this->version = $valor[0];
	return true;
	}else{
	return FALSE;
	}
	
}

### Konqueror

	function EsKonqueror(){
	if(preg_match("/Konqueror/i",$this->useragent)){
	$valor = explode(" ",stristr($this->useragent,"Konqueror"));
	$valor = explode("/",$valor[0]);
	$this->nav_tipo = $valor[0];
	$this->version = str_replace(")","",$valor[1]);
	return TRUE;
	}else{
	return FALSE;
	}
	
}

	
### MSIE

function EsMSIE(){
 $bits ="";
 if((preg_match("/Win64/", $this->useragent))&& (preg_match("/Win64/", $this->useragent))){
 $bits = "x64";
 $this->bits = $bits;
 }

	if(preg_match("/msie/i", $this->useragent) && !preg_match("/opera/i",$this->useragent)){
	$this->nav_tipo = "MSIE";
	$valor = explode(" ",stristr($this->useragent,"msie"));
	$this->nav_tipo = $valor[0];
	$this->version = substr($valor[1], 0, -1);
			
	return TRUE;
	}else{
	return FALSE;
	}
	
}
	
	
	
// IE 11 02/12/2013	
	

function EsIE11(){

 if(preg_match('/rv:11/i',$this->useragent) && preg_match('/trident/i',$this->useragent)){
	$this->nav_tipo = "IE"; 
	$this->version = "11";
	return TRUE;
	}else{
	return FALSE;
	}
	
}






### MSIE Pocket PC
	function EsMSPIE(){
	if(preg_match("/mspie/i",$this->useragent) || preg_match("/pocket/i", $this->useragent)){
	$valor = explode(" ",stristr($this->useragent,"mspie"));
	$this->nav_tipo = "MSPIE"; 
	$this->sistema_op = "WindowsCE"; 
	if (preg_match("/mspie/i", $this->useragent))
	$this->version = $valor[1];
	else{
	$valor = explode("/",$this->useragent);
	$this->version = $valor[1];
	}
	return TRUE;
	}else{
	return FALSE;
	}
	
}
	
### ICab (Mac)
	function EsIcab(){
	if(preg_match("/icab/i",$this->useragent)){
	$valor = explode(" ",stristr($this->useragent,"icab"));
	$this->nav_tipo = $valor[0];
	$this->version = $valor[1];
	return TRUE;
	}else{
	return FALSE;
	}
	
}
	
### OmniWeb (Mac)
	function EsOmniWeb(){
	if(preg_match("/omniweb/i",$this->useragent)){
	$valor = explode("/",stristr($this->useragent,"omniweb"));
	$this->nav_tipo = $valor[0];
	$this->version = $valor[1];
	return TRUE;
	}
	else{
	return FALSE;
	}
	
}
	
### Lynx

	function EsLynx(){
	if(preg_match("/libwww/i", $this->useragent)){
	
	if(preg_match("/amaya/i", $this->useragent)){
	$valor = explode("/",stristr($this->useragent,"amaya"));
	$this->nav_tipo = "Amaya"; 
	$valor = explode(" ", $valor[1]);
	$this->version = $valor[0];
	}else{
	$valor = explode("/",$this->useragent);
	$this->nav_tipo = "Lynx"; 
	$this->version = $valor[1];
	}
	return TRUE;
	}else{
	return FALSE;
	}
	
}
	
### Safari

	function EsSafari(){
	if(preg_match("/safari/i", $this->useragent)){
	$this->nav_tipo = "Safari"; 

 if(preg_match("/Version\//", $_SERVER['HTTP_USER_AGENT'])){
 $valor = explode("Version/", $_SERVER['HTTP_USER_AGENT']);
 $valor = explode(" ",$valor[1]);
	$this->version = $valor[0];
 }else{
	$this->version = "";
 }
	return TRUE;
	}else{
	return FALSE;
	}
	
}
	
### Chrome

	function EsChrome(){
	if(preg_match("/Chrome\//", $this->useragent)){
	$this->nav_tipo = "Chrome"; 
 
 if(preg_match("/Chrome\//", $_SERVER['HTTP_USER_AGENT'])) {
 $valor = explode("Chrome/", $_SERVER['HTTP_USER_AGENT']);
 $valor = explode(" ",$valor[1]);
	$this->version = $valor[0];
 }else{
	$this->version = "";
 }
	return TRUE;
	}else{
	return FALSE;
	}
	
}
	
### Compatibilidad Css2/3

	function EsW3css(){
 if(preg_match('/W3C_CSS_Validator/i',$this->useragent)){
	$this->nav_tipo = "W3css"; 
	$this->version = "2.0";
	return TRUE;
	}else{
	return FALSE;
	}
	
}


### Funciones de salida
	
	function miNavegador(){
	$this->det_sistemaOperativo();
	$this->EsOpera();
	$this->EsFirefox();
	$this->EsKonqueror();
	$this->EsMSIE();
	$this->EsIE11();
	$this->EsMSPIE();
	$this->EsIcab();
	$this->EsOmniWeb();
	$this->EsLynx();
	$this->EsSafari();
	$this->EsChrome();
	$this->EsW3css();
	
 return array(
 'nav_tipo' => $this->nav_tipo, 
	'version' => $this->version, 
	'sistema_op' => $this->sistema_op, 
	'bits' => $this->bits); 
	}
} ## FIN CLASE
?>