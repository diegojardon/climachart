<?php

/*
*		Actualización de elemento para la plataforma bemtec.
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

    $data = json_decode(file_get_contents("php://input"));
  	$esMasivoElemento = mysql_real_escape_string($data->esMasivoElemento);
    $kTotal = mysql_real_escape_string($data->kTotal);
    $mTotal = mysql_real_escape_string($data->mTotal);
    $mParcial = mysql_real_escape_string($data->mParcial);

    if(isset($_SESSION['idElemento'])){

      $idElemento = $_SESSION['idElemento'];

      $query = "UPDATE elemento SET esMasivoElemento = '".$esMasivoElemento."', kTotal = '".$kTotal."',
      mTotal = '".$mTotal."', mParcial = '".$mParcial."' WHERE idElemento = '$idElemento'";

      $result = mysql_query($query);

      if($result === TRUE){
        $resultado["response"] = Constantes::EXITO;
        if(isset($_SESSION['direccionElemento'])){
          $resultado["direccionElemento"] = $_SESSION['direccionElemento'];
        }else{
          $resultado["direccionElemento"] = "SIN_SESION";
        }
      }else{
        $resultado["response"] = Constantes::ERROR;
      }
    }else{
      $resultado["response"] = Constantes::ERROR_SESION_EXPIRADA;
    }

    echo json_encode($resultado);

?>
