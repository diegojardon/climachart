<?php

/*
*		Servicio REST para consultar la configuración del cálculo actual.
*
* 	@author: Diego Jardón
* 	@creationDate: 03-Feb-2018
* 	@version: 1.0
*
*/

	session_start();

	require("conexion.php");
	require("constantes.php");
	require("cors.php");

	$data = json_decode(file_get_contents("php://input"));

	$idCalculo = $_SESSION['idCalculo'];

	$result = mysql_query("SELECT * FROM elemento WHERE idCalculo = '".$idCalculo."'",$link);

	if($result === FALSE){
			$resultado["response"] = Constantes::ERROR;
	}else{
		$totalElemento = mysql_num_rows($result);
		if($totalElemento > 0){
		   $i=0;
			 while($info = mysql_fetch_assoc($result)){
				 	 $resultado[$i]["idElemento"] = $info["idElemento"];
					 $resultado[$i]["nombreElemento"] = $info["nombreElemento"];
					 $resultado[$i]["tipoElemento"] = $info["tipoElemento"];
					 $resultado[$i]["direccionElemento"] = $info["direccionElemento"];
					 $resultado[$i]["esHomogeneoElemento"] = $info["esHomogeneoElemento"];
					 $resultado[$i]["areaElemento"] = $info["areaElemento"];
					 $resultado[$i]["esMasivoElemento"] = $info["esMasivoElemento"];
					 $resultado[$i]["kTotal"] = $info["kTotal"];
					 $resultado[$i]["mTotal"] = $info["mTotal"];
					 $resultado[$i]["mParcial"] = $info["mParcial"];
				 	 $i++;
			 }

			$resultado["response"] = Constantes::EXITO;

		}else{
			$resultado["response"] = Constantes::ERROR;
		}
	}

	echo json_encode($resultado);
?>
