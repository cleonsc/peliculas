<?php 
	session_start();

	/* genero un string largo, y como parametro 
	 * le paso la fecha actual con microsegundos (microtime).
	 * luego con substr lo acorto a seis caracteres
	 */
	$ranStr = substr( sha1( microtime() ),0,6); 

	//Guardo el valor del captcha en una variable de sesion
	$_SESSION['captcha'] = $ranStr; 

	/*
	 * creo la imagen con php...
	 * fondo_captcha.jpg debe ser una imagen existente 
	 */
	$newImage = imagecreatefromjpeg( "fondo_captcha.jpg" ); 


	// la funcion imagecolorallocate ( $imagen , rojo , verde , azul ) genera un color 
	$txtColor = imagecolorallocate($newImage, 0, 0, 200); 

	/*
	 * que luego lo usamos para colorear el string
	 * bool imagestring ( resource $image , int $font , int $x , int $y , string $string , int $color )
	 */
	imagestring($newImage, 5, 30, 8, $ranStr, $txtColor); 

	//indico la cabecera
	header( "Content-type: image/jpeg" );

	//creo la imagen
	imagejpeg($newImage); 
?>