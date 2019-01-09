<?php

/*
*		Servicio REST para agregar componentes al cálculo en la sesión actual.
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
	$materialComponente = mysql_real_escape_string($data->materialComponente);
	$espesorComponente = mysql_real_escape_string($data->espesorComponente);
	$conductividadComponente = mysql_real_escape_string($data->conductividadComponente);
	$aislanteComponente = mysql_real_escape_string($data->aislanteComponente);
	$areaNHComponente = mysql_real_escape_string($data->areaNHComponente);
	$fraccionNHComponente = mysql_real_escape_string($data->fraccionNHComponente);

	//Se inserta el elemento en la BD

  if(isset($_SESSION['idElemento'])){

		$idElemento = $_SESSION['idElemento'];

		$query = "INSERT INTO componente (`idComponente`,`idElemento`,`materialComponente`,`espesorComponente`,`conductividadComponente`, `aislanteComponente`, `areaNHComponente`, `fraccionNHComponente`)
	            VALUES (NULL, '$idElemento', '$materialComponente', '$espesorComponente', '$conductividadComponente', '$aislanteComponente', '$areaNHComponente', '$fraccionNHComponente')";

		$result = mysql_query($query);

		if($result === TRUE){
	 		$resultado["response"] = Constantes::EXITO;
		}else{
			 $resultado["response"] = Constantes::ERROR;
		}
	}else{
		$resultado["response"] = Constantes::ERROR_SESION_EXPIRADA;
	}

	echo json_encode($resultado);
?>
