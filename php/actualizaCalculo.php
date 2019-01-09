<?php

/*
*		Actualización de cálculo para la plataforma bemtec.
*
* 	@author: Diego Jardón
* 	@creationDate: 02-Feb-2018
* 	@version: 1.0
*
*/

    session_start();

    require("conexion.php");
    require("constantes.php");
    require("cors.php");

    if(!isset($_SESSION['idCalculo']))
      $idCalculo = 0;
    else
      $idCalculo = $_SESSION['idCalculo'];

    $data = json_decode(file_get_contents("php://input"));
  	$nombrePropietario = mysql_real_escape_string($data->nombrePropietario);
    $direccionPropietario = mysql_real_escape_string($data->direccionPropietario);
    $cpPropietario = mysql_real_escape_string($data->cpPropietario);
    $telefonoPropietario = mysql_real_escape_string($data->telefonoPropietario);
    $nombreEdificio = mysql_real_escape_string($data->nombreEdificio);
    $direccionEdificio = mysql_real_escape_string($data->direccionEdificio);
    $numNivelesEdificio = mysql_real_escape_string($data->numNivelesEdificio);
    $estadoEdificio = mysql_real_escape_string($data->estadoEdificio);
    $ciudadEdificio = mysql_real_escape_string($data->ciudadEdificio);
    $latitudEdificio = mysql_real_escape_string($data->latitudEdificio);

    if($idCalculo != 0){
      $query = "UPDATE calculo SET nombrePropietario = '".$nombrePropietario."', direccionPropietario = '".$direccionPropietario."',
      cpPropietario = '".$cpPropietario."', telefonoPropietario = '".$telefonoPropietario."', nombreEdificio = '".$nombreEdificio."',
      direccionEdificio = '".$direccionEdificio."',numNivelesEdificio = '".$numNivelesEdificio."', cve_est = '".$estadoEdificio."', cve_mun = '".$ciudadEdificio."',
      latitud = '".$latitudEdificio."' WHERE idCalculo = '$idCalculo'";

      $result = mysql_query($query);

      if($result === TRUE){
        $resultado["response"] = Constantes::EXITO;
        $_SESSION['datosGenerales']='ok';
      }else{
        $resultado["response"] = Constantes::ERROR;
      }
    }else{
      $resultado["response"] = Constantes::ERROR;
    }

    echo json_encode($resultado);

?>
