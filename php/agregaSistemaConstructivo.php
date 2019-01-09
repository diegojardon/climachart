<?php

/*
*		Servicio REST para agregar sistemas constructivos al cálculo en la sesión actual.
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
	$nombreElemento = mysql_real_escape_string($data->nombreElemento);
	$tipoElemento = mysql_real_escape_string($data->tipoElemento);
	$areaElemento = mysql_real_escape_string($data->areaElemento);

	//Se inserta el elemento en la BD

	$result = mysql_query("SELECT tipoElemento, direccionElemento, esHomogeneoElemento, esMasivoElemento, kTotal, mTotal, mParcial FROM elemento WHERE idElemento = '".$idElemento."'",$link);
	$totalElem = mysql_num_rows($result);
	
	if($totalElem > 0){
		if(isset($_SESSION['idCalculo'])){

			$idCalculo = $_SESSION['idCalculo'];
			$info = mysql_fetch_assoc($result);

			$query = "INSERT INTO elemento (`idElemento`,`nombreElemento`,`tipoElemento`,`direccionElemento`,`idCalculo`, `esHomogeneoElemento`,`areaElemento`,`esMasivoElemento`,`kTotal`,`mTotal`,`mParcial`)
		            VALUES (NULL, '$nombreElemento', '$info["tipoElemento"]' , '$info["direccionElemento"]', '$idCalculo', '$info["esHomogeneoElemento"]', '$areaElemento', '$info["esMasivoElemento"]',
								'$info["kTotal"]', '$info["mTotal"]', '$info["mParcial"]')";

			$result = mysql_query($query);

			$_SESSION['idElemento'] = mysql_insert_id();

		  if($result === TRUE){
		 		$resultado["response"] = Constantes::EXITO;
				$resultado["esHomogeneo"] = $esHomogeneoElemento;

				$_SESSION["esHomogeneo"] = $esHomogeneoElemento;
				$_SESSION["nombreElemento"] = $nombreElemento;
				$_SESSION["direccionElemento"] = $direccionElemento;
				$_SESSION["areaElemento"] = $areaElemento;
			}else{
				 $resultado["response"] = Constantes::ERROR;
			}
		}else{
			$resultado["response"] = Constantes::ERROR_SESION_EXPIRADA;
		}
	}


	echo json_encode($resultado);
?>
