<?php

/*
*		Servicio REST para consultar municipios de acuerdo a un estado.
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
	$claveEntidad = mysql_real_escape_string($data->claveEntidad);

	$result = mysql_query("SELECT cve_mun,nom_mun,latitud FROM municipio WHERE cve_ent = '".$claveEntidad."'",$link);

	if($result === FALSE){
			$resultado["response"] = Constantes::ERROR;
	}else{
		$totalUsu = mysql_num_rows($result);
		if($totalUsu > 0){
		   $i=0;
			 while($info = mysql_fetch_assoc($result)){
				 	 $resultado[$i]["claveMunicipio"] = $info["cve_mun"];
					 $resultado[$i]["nombreMunicipio"] = $info["nom_mun"];
					 $resultado[$i]["latitud"] = $info["latitud"];
				 	 $i++;
			 }

			$resultado["response"] = Constantes::EXITO;

		}else{
			$resultado["response"] = Constantes::ERROR;
		}
	}

	echo json_encode($resultado);
?>
