<?php 
	session_start();
	//esta variable contiene el resultado de la comparacion
	$resultado ='';
	
	//si se ingreso un codigo...
	if(isset($_POST['codigo'])):
		//y este es igual a la variable de sesion con el valor correcto 
		if($_POST['codigo'] == $_SESSION['captcha']):
			//muestro un mensaje de exito
			$resultado = "Valido!!";
		else:
			$resultado = "Invalido!!";
		endif; 
	endif;
?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Captcha</title>
		<style type='text/css'>
			table tr td{
				border:1px solid black;
				text-align: center;
			}
		</style>
	</head>
	<body>
		<!-- asigno a la misma pagina para procesar el resultado -->
		<form action="index.php" method='POST'>
			<table>
				<tr>
					<td><input type="text" name="codigo" required></td>
					<!-- notese que la fuente de la imagen se crea en captcha.php -->
					<td><img src="captcha.php"/></td>
				</tr>
				<tr><td colspan="2"><input type="submit" value="Comprobar"></td></tr>
				<!-- se muestra si los datos ingresados fueron correctos -->
				<tr><td colspan="2" id="resultado"><?php echo $resultado; ?></td></tr>
			</table>
		</form>

	</body>
</html>