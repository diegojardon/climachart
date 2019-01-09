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

  if(isset($_SESSION['idCalculo'])){

    $idCalculo = $_SESSION['idCalculo'];

    $result = mysql_query("SELECT count(direccionElemento) AS total, direccionElemento FROM elemento WHERE idCalculo = '".$idCalculo."' GROUP BY direccionElemento",$link);

    if($result === FALSE){
        $resultado["response"] = Constantes::ERROR;
    }else{
      $totalUsu = mysql_num_rows($result);
      if($totalUsu > 0){
         while($info = mysql_fetch_assoc($result)){
             if($info["direccionElemento"] == "Norte"){
               $resultado["totalElementosNorte"] = $info["total"];
             }
             if($info["direccionElemento"] == "Sur"){
               $resultado["totalElementosSur"] = $info["total"];
             }
             if($info["direccionElemento"] == "Este"){
               $resultado["totalElementosEste"] = $info["total"];
             }
             if($info["direccionElemento"] == "Oeste"){
               $resultado["totalElementosOeste"] = $info["total"];
             }
             if($info["direccionElemento"] == "Techo"){
               $resultado["totalElementosTecho"] = $info["total"];
             }
             if($info["direccionElemento"] == "TechoI"){
              $resultado["totalElementosTechoInterior"] = $info["total"];
            }
         }

        $resultado["response"] = Constantes::EXITO;

      }else{
        $resultado["response"] = Constantes::ERROR;
      }
    }
  }else{
    $resultado["response"] = Constantes::ERROR_SESION_EXPIRADA;
  }

	echo json_encode($resultado);
?>
