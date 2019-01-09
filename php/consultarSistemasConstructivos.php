<?php

/*
*		Consulta de totales de elementos (muros, puertas y ventanas) para la plataforma bemtec.
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

  if(isset($_SESSION['idUsuario'])){

    $idUsuario = $_SESSION['idUsuario'];

    $result = mysql_query("SELECT idElemento, nombreElemento, direccionElemento FROM elemento,calculo WHERE elemento.idCalculo = calculo.idCalculo AND calculo.idUsuario = '".$idUsuario."'",$link);
    $totalElem = mysql_num_rows($result);
    if($totalElem > 0){
      $i = 0;
      while($info = mysql_fetch_assoc($result)){
        $resultado[$i]["idElemento"] = $info["idElemento"];
        $resultado[$i]["nombreElemento"] = $info["nombreElemento"];
        $resultado[$i]["direccionElemento"] = $info["direccionElemento"];
        $i++;
      }

      $resultado["response"] = Constantes::EXITO;
    }

  }else{
    $resultado["response"] = Constantes::ERROR_SESION_EXPIRADA;
  }

	echo json_encode($resultado);
?>
