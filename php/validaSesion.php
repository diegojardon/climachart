<?php

	/*
	*		Servicio REST para validar si existe sesión en la plataforma bemtec.
	*
	* 	@author: Diego Jardón
	* 	@creationDate: 27-Jan-2018
	* 	@version: 1.0
	*
	*/
	session_start();

	if(!isset($_SESSION['login'])){
		$resultado["sesion"] = 0;
		//header('Location: ../web/view/login.html');
	}else{
		$ahora = time();
		if ($ahora > $_SESSION['expire']) {
            session_destroy();
			$resultado["sesion"] = 0;
			//header('Location: ../web/view/login.html');
    }else{
			$resultado["sesion"] = 1;
			$resultado["idUsuario"] = $_SESSION['idUsuario'];
			$resultado["usuarioUsuario"] = $_SESSION['usuarioUsuario'];
			$resultado["estatusUsuario"] = $_SESSION['estatusUsuario'];
			$resultado["nombreNormaEnergetica"] = $_SESSION['nombreNormaEnergetica'];
			$resultado["areaElemento"] = $_SESSION['areaElemento'];
			$resultado["datosGenerales"] = $_SESSION['datosGenerales'];
			$resultado["ultimaDireccion"] = $_SESSION['direccionElemento'];
		}
	}

	echo json_encode($resultado);
?>
