<?php

/*
*		Servicio REST para borrar elementos.
*
* 	@author: Diego Jardón
* 	@creationDate: 17-Feb-2018
* 	@version: 1.0
*
*/

	session_start();

    require("conexion.php");
	require("constantes.php");
	require("cors.php");

	$data = json_decode(file_get_contents("php://input"));
	$idElemento = mysql_real_escape_string($data->idElemento);

	//Se borra el elemento de la BD

	if(isset($_SESSION['idCalculo'])){

		$query = "DELETE FROM elemento WHERE idElemento = " . $idElemento;

		$result = mysql_query($query);

	  if($result === TRUE){
	 		$resultado["response"] = Constantes::EXITO;
		}else{
			 $resultado["response"] = Constantes::ERROR;
		}
	}else{
		$resultado["response"] = Constantes::ERROR_BORRAR_ELEMENTO;
	}

	echo json_encode($resultado);
?>
