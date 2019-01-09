<?php

/*
*		Servicio REST para realizar login a la plataforma bemtec.
*
* 	@author: Diego Jardón
* 	@creationDate: 27-Jan-2018
* 	@version: 1.0
*
*/

	session_start();

	require("conexion.php");
	require("constantes.php");
	require("cors.php");

	$data = json_decode(file_get_contents("php://input"));
	$usuarioUsuario = mysql_real_escape_string($data->usuarioUsuario);
	$passwordUsuario = mysql_real_escape_string($data->passwordUsuario);

	/*$usuarioUsuario = "dynosaur1218@hotmail.com";
  $passwordUsuario = "diego123";*/

	$result = mysql_query("SELECT idUsuario, institucionUsuario, estatusUsuario FROM usuario WHERE usuarioUsuario = '".$usuarioUsuario."' AND passwordUsuario = '".$passwordUsuario."'",$link);

	if($result === FALSE){
			$resultado["response"] = Constantes::ERROR;
	}else{
		$totalUsu = mysql_num_rows($result);
		if($totalUsu == 1){
			$info = mysql_fetch_assoc($result);
			$resultado["id"] = $info["idUsuario"];
			$resultado["response"] = Constantes::EXITO;

			$_SESSION['login']='ok';
			$_SESSION['idUsuario'] = $info["idUsuario"];
			$_SESSION['usuarioUsuario'] = $usuarioUsuario;
			$_SESSION['estatusUsuario'] = $info["estatusUsuario"];

			// Tomamos el tiempo en el que se inicio sesion
			$_SESSION['start'] = time();

      // Finalizar la sesion en 6 horas, apartir del tiempo de inicio (720 minutos)
      $_SESSION['expire'] = $_SESSION['start'] + (720 * 60);

			//Se inserta un registro en la tabla de accesos

			$idUsuario = $info['idUsuario'];

			$query = "INSERT INTO accion (`idAccion`,`idUsuario`,`idTipoAccion`,`fechaAccion`)
					  VALUES (NULL, '$idUsuario', 2 , NOW())";

			$result = mysql_query($query);

		}else{
			$resultado["response"] = Constantes::ERROR_USUARIO_Y_O_PASSWORD_INCORRECTOS;
		}
	}

	echo json_encode($resultado);
?>
