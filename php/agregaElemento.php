<?php

/*
*		Servicio REST para agregar elementos homogéneos al cálculo en la sesión actual.
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
	$nombreElemento = mysql_real_escape_string($data->nombreElemento);
	$tipoElemento = mysql_real_escape_string($data->tipoElemento);
	$direccionElemento = mysql_real_escape_string($data->direccionElemento);
	$esHomogeneoElemento = mysql_real_escape_string($data->esHomogeneoElemento);
	$areaElemento = mysql_real_escape_string($data->areaElemento);
	$coeficienteSombra = mysql_real_escape_string($data->coeficienteSombra);
	$tipoSombra = mysql_real_escape_string($data->tipoSombra);
	$LVoladoMas = mysql_real_escape_string($data->LVoladoMas);
	$HVoladoMas = mysql_real_escape_string($data->HVoladoMas);
	$AVoladoMas = mysql_real_escape_string($data->AVoladoMas);
	$LVoladoLimite = mysql_real_escape_string($data->LVoladoLimite);
	$HVoladoLimite = mysql_real_escape_string($data->HVoladoLimite);
	$WVoladoLimite = mysql_real_escape_string($data->WVoladoLimite);
	$AVoladoLimite = mysql_real_escape_string($data->AVoladoLimite);
	$ERemetida = mysql_real_escape_string($data->ERemetida);
	$PRemetida = mysql_real_escape_string($data->PRemetida);
	$WRemetida = mysql_real_escape_string($data->WRemetida);
	$LParteluces = mysql_real_escape_string($data->LParteluces);
	$WParteluces = mysql_real_escape_string($data->WParteluces);
	$KVentana = mysql_real_escape_string($data->KVentana);
	$MVentana = mysql_real_escape_string($data->MVentana);

	//Se inserta el elemento en la BD

	if(isset($_SESSION['idCalculo'])){

		$idCalculo = $_SESSION['idCalculo'];
		$normaEnergetica = $_SESSION['nombreNormaEnergetica'];

		$query = "INSERT INTO elemento (`idElemento`,`nombreElemento`,`tipoElemento`,`direccionElemento`,`idCalculo`,
		                                `esHomogeneoElemento`,`areaElemento`,`coeficienteSombra`, `tipoSombra`,
										`LVoladoMas`, `HVoladoMas`, `AVoladoMas`, `LVoladoLimite`, `HVoladoLimite`, 
										`WVoladoLimite`, `AVoladoLimite`, `ERemetida`, `PRemetida`, `WRemetida`,
										`LParteluces`, `WParteluces`,`kTotal`,`mTotal`)
	            VALUES (NULL, '$nombreElemento', '$tipoElemento' , '$direccionElemento', '$idCalculo', '$esHomogeneoElemento', '$areaElemento',
					    '$coeficienteSombra', '$tipoSombra', '$LVoladoMas', '$HVoladoMas', '$AVoladoMas', '$LVoladoLimite',
						'$HVoladoLimite', '$WVoladoLimite', '$AVoladoLimite', '$ERemetida', '$PRemetida', '$WRemetida',
						'$LParteluces', '$WParteluces', '$KVentana', '$MVentana')";

		$result = mysql_query($query);

		$_SESSION['idElemento'] = mysql_insert_id();

	  if($result === TRUE){
	 		$resultado["response"] = Constantes::EXITO;
			$resultado["esHomogeneo"] = $esHomogeneoElemento;
			$resultado["normaEnergetica"] = $normaEnergetica;
			$resultado["tipoElemento"] = $tipoElemento;
			$resultado["direccionElemento"] = $direccionElemento;
			

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

	echo json_encode($resultado);
?>
