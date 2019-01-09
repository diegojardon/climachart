<?php

/*
*		Alta de acciones para la plataforma bemtec.
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
	$idTipoAccion = mysql_real_escape_string($data->idTipoAccion);

  if($idTipoAccion == '3' || $idTipoAccion == '4'){
      if(isset($_SESSION['idUsuario'])){
        $idUsuario = $_SESSION['idUsuario'];
      }else{
        $idUsuario = "0"; //Usuario visitante
      }
  }else{
    $idUsuario = "0"; //Usuario visitante
  }

  $query = "INSERT INTO accion (`idAccion`,`idUsuario`,`idTipoAccion`,`fechaAccion`)
            VALUES (NULL, '$idUsuario', '$idTipoAccion' , NOW())";

  $result = mysql_query($query);

  if($result === TRUE){

    if($idTipoAccion == 3){
      $_SESSION['nombreNormaEnergetica']="NOM 008-ENER 2001";

      $query = "INSERT INTO calculo (`idCalculo`,`idUsuario`,`fechaCalculo`,`normaCalculo`)
                VALUES (NULL, '$idUsuario', NOW(), 2001)";

      $result = mysql_query($query);

      $_SESSION['idCalculo'] = mysql_insert_id();
    }
    if($idTipoAccion == 4){
      $_SESSION['nombreNormaEnergetica']="NOM 020-ENER 2011";

      $query = "INSERT INTO calculo (`idCalculo`,`idUsuario`,`fechaCalculo`,`normaCalculo`)
                VALUES (NULL, '$idUsuario', NOW(), 2011)";

      $result = mysql_query($query);

      $_SESSION['idCalculo'] = mysql_insert_id();
    }



     $resultado["response"] = Constantes::EXITO;
  }else{
    $resultado["response"] = Constantes::ERROR;
  }

	echo json_encode($resultado);
?>
