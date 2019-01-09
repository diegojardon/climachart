<?php

/*
*		Servicio REST para agregar elementos al cálculo en la sesión actual.
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
	$tipoElemento = mysql_real_escape_string($data->tipoElemento);
	$direccionElemento = mysql_real_escape_string($data->direccionElemento);
	$totalElementos = mysql_real_escape_string($data->totalElementos);

	//Se agrega el elemento con su configuración al array

	if(!isset($_SESSION['elementos'])){
		$elementos = array();
	}else{
		$elementos = $_SESSION['elementos'];
	}

	$llave = $tipoElemento . $direccionElemento;
	$elementos[$llave] += intval($totalElementos);

	$_SESSION['elementos'] = $elementos;

	$resultado["response"] = $elementos[$llave];

	echo json_encode($resultado);
?>
