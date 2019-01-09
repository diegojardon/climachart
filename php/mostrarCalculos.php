<?php

/*
*		Cálculo para Edificio de Referencia (calor y radiación).
*
* 	@author: Diego Jardón
* 	@creationDate: 2-Junio-2018
* 	@version: 1.0
*
*/

  session_start();

  require("conexion.php");
  require("constantes.php");
  require("cors.php");
  
  $data = json_decode(file_get_contents("php://input"));

  //Se valida que exista y se obtiene el idCalculo almacenado en la sesión

  if(isset($_SESSION['idCalculo'])){

    $idCalculo = $_SESSION['idCalculo'];
 
    //Se obtiene el número de niveles, la latitud y la norma

    $resultCalculo = mysql_query("SELECT numNivelesEdificio, latitud, normaCalculo FROM calculo WHERE idCalculo = '".$idCalculo."'",$link);
    if($resultCalculo === FALSE){
			$resultado["response"] = Constantes::ERROR;
    }else{

      $totalUsu = mysql_num_rows($resultCalculo);
      if($totalUsu > 0){
        $i=0;
        while($info = mysql_fetch_assoc($resultCalculo)){
            $numNivelesEdificio = $info["numNivelesEdificio"];
            $latitud = $info["latitud"];
            $norma = $info["normaCalculo"];
            $i++;
        }

        echo "Número Niveles Edificio: " . $numNivelesEdificio . "</br>";
        echo "Latitud: " . $latitud . "<br/><br/>";

        //Obtenemos la suma de áreas para los elementos por orientación

        echo "SUMA DE AREAS PARA LOS ELEMENTOS POR ORIENTACIÓN<br/><br/>";
        $resultAreaTotal = mysql_query("SELECT direccionElemento, sum(areaElemento) AS areaTotal FROM elemento WHERE idCalculo = '".$idCalculo."' GROUP BY direccionElemento",$link);
        if($resultAreaTotal === FALSE){
          $resultado["response"] = Constantes::ERROR;
        }else{
    
          $totalUsu = mysql_num_rows($resultAreaTotal);
          if($totalUsu > 0){
            $i=0;
            while($info = mysql_fetch_assoc($resultAreaTotal)){
                $resultado[$i]["direccionElemento"] = $info["direccionElemento"];
                $resultado[$i]["areaTotal"] = $info["areaTotal"];
                echo "Direccion Elemento: " . $info["direccionElemento"] . "<br/>";
                echo "Área Total: " . $info["areaTotal"] . "<br/>";
                $i++;
            }

            $resultadosCalculos = array(0.0, 0.0, 0.0, 0.0);
            $resultadosCalculosTmp = array(0.0, 0.0, 0.0, 0.0);
          
            echo "INICIO DE CÁLCULOS<br/><br/><br/>";

            echo "CALCULO DE REFERENCIA: <br/>";
            //Se realizan los cálculos correspondientes
            for($j=0; $j<$i; $j++){
              if($resultado[$j]["direccionElemento"] == "Norte"){
                $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Norte", $norma, $numNivelesEdificio, $latitud, $resultado[$j]["areaTotal"], $resultadosCalculos, "Referencia", 0, $link);
                //$resultadosCalculos[0] += $resultadosCalculosTmp[0];
                $resultadosCalculos[1] += $resultadosCalculosTmp[1];
                //$resultadosCalculos[2] += $resultadosCalculosTmp[2];
                $resultadosCalculos[3] += $resultadosCalculosTmp[3];
                continue;
              }
              if($resultado[$j]["direccionElemento"] == "Sur"){
                $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Sur", $norma, $numNivelesEdificio, $latitud, $resultado[$j]["areaTotal"], $resultadosCalculos, "Referencia", 0, $link);
                //$resultadosCalculos[0] += $resultadosCalculosTmp[0];
                $resultadosCalculos[1] += $resultadosCalculosTmp[1];
                //$resultadosCalculos[2] += $resultadosCalculosTmp[2];
                $resultadosCalculos[3] += $resultadosCalculosTmp[3];
                continue;
              }
              if($resultado[$j]["direccionElemento"] == "Este"){
                $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Este", $norma, $numNivelesEdificio, $latitud, $resultado[$j]["areaTotal"], $resultadosCalculos, "Referencia", 0, $link);
                //$resultadosCalculos[0] += $resultadosCalculosTmp[0];
                $resultadosCalculos[1] += $resultadosCalculosTmp[1];
                //$resultadosCalculos[2] += $resultadosCalculosTmp[2];
                $resultadosCalculos[3] += $resultadosCalculosTmp[3];
                continue;
              }
              if($resultado[$j]["direccionElemento"] == "Oeste"){
                $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Oeste", $norma, $numNivelesEdificio, $latitud, $resultado[$j]["areaTotal"], $resultadosCalculos, "Referencia", 0, $link);
                //$resultadosCalculos[0] += $resultadosCalculosTmp[0];
                $resultadosCalculos[1] += $resultadosCalculosTmp[1];
                //$resultadosCalculos[2] += $resultadosCalculosTmp[2];
                $resultadosCalculos[3] += $resultadosCalculosTmp[3];
                continue;
              }
              if($resultado[$j]["direccionElemento"] == "Techo"){
                $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Techo", $norma, $numNivelesEdificio, $latitud, $resultado[$j]["areaTotal"], $resultadosCalculos, "Referencia", 0, $link);
                //$resultadosCalculos[0] += $resultadosCalculosTmp[0];
                $resultadosCalculos[1] += $resultadosCalculosTmp[1];
                //$resultadosCalculos[2] += $resultadosCalculosTmp[2];
                $resultadosCalculos[3] += $resultadosCalculosTmp[3];
                continue;
              }
              if($resultado[$j]["direccionElemento"] == "TechoI"){
                $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "TechoI", $norma, $numNivelesEdificio, $latitud, $resultado[$j]["areaTotal"], $resultadosCalculos, "Referencia", 0, $link);
                //$resultadosCalculos[0] += $resultadosCalculosTmp[0];
                $resultadosCalculos[1] += $resultadosCalculosTmp[1];
                //$resultadosCalculos[2] += $resultadosCalculosTmp[2];
                $resultadosCalculos[3] += $resultadosCalculosTmp[3];
                continue;
              }
            }

            echo "CALCULO PROYECTADO: <br/>";

            $resultAreaTotal = mysql_query("SELECT idElemento, direccionElemento, areaElemento FROM elemento WHERE idCalculo = '".$idCalculo."'",$link);
            if($resultAreaTotal === FALSE){
              $resultado["response"] = Constantes::ERROR;
            }else{
    
              $totalUsu = mysql_num_rows($resultAreaTotal);
              if($totalUsu > 0){
                $i=0;
                while($info = mysql_fetch_assoc($resultAreaTotal)){
                    $resultado[$i]["direccionElemento"] = $info["direccionElemento"];
                    $resultado[$i]["areaTotal"] = $info["areaElemento"];
                    echo "Id Elemento: " . $info["idElemento"] . "<br/>";
                    echo "Direccion Elemento: " . $info["direccionElemento"] . "<br/>";
                    echo "Área Total: " . $info["areaElemento"] . "<br/>";

                    if($resultado[$i]["direccionElemento"] == "Norte"){
                      $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Norte", $norma, $numNivelesEdificio, $latitud, $resultado[$i]["areaTotal"], $resultadosCalculos, "Proyectado", $info["idElemento"], $link);
                      $resultadosCalculos[0] += $resultadosCalculosTmp[0];
                      //$resultadosCalculos[1] += $resultadosCalculosTmp[1];
                      $resultadosCalculos[2] += $resultadosCalculosTmp[2];
                      //$resultadosCalculos[3] += $resultadosCalculosTmp[3];
                      continue;
                    }
                    if($resultado[$i]["direccionElemento"] == "Sur"){
                      $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Sur", $norma, $numNivelesEdificio, $latitud, $resultado[$i]["areaTotal"], $resultadosCalculos, "Proyectado", $info["idElemento"], $link);
                      $resultadosCalculos[0] += $resultadosCalculosTmp[0];
                      //$resultadosCalculos[1] += $resultadosCalculosTmp[1];
                      $resultadosCalculos[2] += $resultadosCalculosTmp[2];
                      //$resultadosCalculos[3] += $resultadosCalculosTmp[3];
                      continue;
                    }
                    if($resultado[$i]["direccionElemento"] == "Este"){
                      $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Este", $norma, $numNivelesEdificio, $latitud, $resultado[$i]["areaTotal"], $resultadosCalculos, "Proyectado", $info["idElemento"], $link);
                      $resultadosCalculos[0] += $resultadosCalculosTmp[0];
                      //$resultadosCalculos[1] += $resultadosCalculosTmp[1];
                      $resultadosCalculos[2] += $resultadosCalculosTmp[2];
                      //$resultadosCalculos[3] += $resultadosCalculosTmp[3];
                      continue;
                    }
                    if($resultado[$i]["direccionElemento"] == "Oeste"){
                      $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Oeste", $norma, $numNivelesEdificio, $latitud, $resultado[$i]["areaTotal"], $resultadosCalculos, "Proyectado", $info["idElemento"], $link);
                      $resultadosCalculos[0] += $resultadosCalculosTmp[0];
                      //$resultadosCalculos[1] += $resultadosCalculosTmp[1];
                      $resultadosCalculos[2] += $resultadosCalculosTmp[2];
                      //$resultadosCalculos[3] += $resultadosCalculosTmp[3];
                      continue;
                    }
                    if($resultado[$i]["direccionElemento"] == "Techo"){
                      $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "Techo", $norma, $numNivelesEdificio, $latitud, $resultado[$i]["areaTotal"], $resultadosCalculos, "Proyectado", $info["idElemento"], $link);
                      $resultadosCalculos[0] += $resultadosCalculosTmp[0];
                      //$resultadosCalculos[1] += $resultadosCalculosTmp[1];
                      $resultadosCalculos[2] += $resultadosCalculosTmp[2];
                      //$resultadosCalculos[3] += $resultadosCalculosTmp[3];
                      continue;
                    }
                    if($resultado[$i]["direccionElemento"] == "TechoI"){
                      $resultadosCalculosTmp = calculoCalorYRadiacion($idCalculo, "TechoI", $norma, $numNivelesEdificio, $latitud, $resultado[$i]["areaTotal"], $resultadosCalculos, "Proyectado", $info["idElemento"], $link);
                      $resultadosCalculos[0] += $resultadosCalculosTmp[0];
                      //$resultadosCalculos[1] += $resultadosCalculosTmp[1];
                      $resultadosCalculos[2] += $resultadosCalculosTmp[2];
                      //$resultadosCalculos[3] += $resultadosCalculosTmp[3];
                      continue;
                    }



                    $i++;
                }
              }
            }

            //Se codifica la respuesta
            $resultado["response"] = Constantes::EXITO;
            $resultado["gananciaCalorProy"] = $resultadosCalculos[0];
            $resultado["gananciaCalorRef"] = $resultadosCalculos[1];
            $resultado["gananciaRadiacionProy"] = $resultadosCalculos[2];
            $resultado["gananciaRadiacionRef"] = $resultadosCalculos[3];

          }else{
            $resultado["response"] = Constantes::ERROR;
          }
        }    

      }else{
        $resultado["response"] = Constantes::ERROR;
      }
    }

    //echo json_encode($resultado);

  }


  /**
   * @param idCalculo -- El identificador del cálculo
   * @param orientacion -- Indica el tipo de direccion. Puede tomar los valores 
   * Norte, Sur, Este, Oeste, Techo y TechoI
   * @param norma -- Indica la norma, puede tomar los valores 2001 o 2011
   * @param numNivelesEdificio -- Indica el numero de niveles del edificio de referencia
   * @param latitud -- Indica latitud del edificio
   * @param areaTotalOrientacion -- Indica la suma del área total por la orientación definida en 
   * el parámetro orientación
   * @param resultadosCalculo -- Sirve para almacenar los resultados de los cálculos para edificio
   * de referencia y proyectado
   * @param tipoCalculo -- Indica si el cálculo es proyectado o de referencia
   * @param idElemento -- Identificador del elemento
   * @param link -- Variable para conexión con Base de Datos
   */
  function calculoCalorYRadiacion($idCalculo, $orientacion, $norma, $numNivelesEdificio, $latitud, $areaTotalOrientacion, $resultadosCalculos, $tipoCalculo, $idElemento, $link){

    //REVISAR CUANDO ES REFERENCIA QUE SE JUNTEN TODOS LOS MUROS Y PUERTAS EN UNA MISMA Y TODAS LAS VENTANAS EN OTRA
    if($tipoCalculo == "Referencia"){
      $queryF = "SELECT tipoElemento, kTotal, esMasivoElemento, tipoSombra, coeficienteSombra, LVoladoMas, 
      HVoladoMas, AVoladoMas, LVoladoLimite, HVoladoLimite, WVoladoLimite, AVoladoLimite, ERemetida,
      PRemetida, WRemetida, LParteluces, WParteluces FROM elemento WHERE idCalculo = '".$idCalculo."' AND direccionElemento = '".$orientacion."'";
    }else{
      $queryF = "SELECT tipoElemento, kTotal, esMasivoElemento, tipoSombra, coeficienteSombra, LVoladoMas, 
      HVoladoMas, AVoladoMas, LVoladoLimite, HVoladoLimite, WVoladoLimite, AVoladoLimite, ERemetida,
      PRemetida, WRemetida, LParteluces, WParteluces FROM elemento WHERE idCalculo = '".$idCalculo."' AND idElemento = '".$idElemento."'";
    }

    $result = mysql_query($queryF,$link);

    echo "Direccion: " . $orientacion . "<br/>";

    $totalUsu = mysql_num_rows($result);
    echo "Total de elementos: " . $totalUsu . "<br/>";
    if($totalUsu > 0){
        $i=0;
        $muroPuertaProcesadaRef = 0;
        $ventanaProcesadaRef = 0;
        while($info = mysql_fetch_assoc($result)){
            $tipoElemento = $info["tipoElemento"];
            $kTotal = $info["kTotal"];
            $masivoLigero = $info["esMasivoElemento"];
            $tipoSombra = $info["tipoSombra"];
            $cs = $info["coeficienteSombra"];
            $lVoladoMas = $info["LVoladoMas"];
            $hVoladoMas = $info["HVoladoMas"];
            $aVoladoMas = $info["AVoladoMas"];
            $lVoladoLimite = $info["LVoladoLimite"];
            $hVoladoLimite = $info["HVoladoLimite"];
            $wVoladoLimite = $info["WVoladoLimite"];
            $aVoladoLimite = $info["AVoladoLimite"];
            $eRemetida = $info["ERemetida"];
            $pRemetida = $info["PRemetida"];
            $wRemetida = $info["WRemetida"];
            $lParteluces = $info["LParteluces"];
            $wParteluces = $info["WParteluces"];

             //Validar que solamente se procese una vez para referencia muro y puerta y ventana
             if($tipoCalculo == "Referencia"){
              if($tipoElemento == "Ventana" || $tipoElemento == "Tragaluz"){
                //echo "VALOR VENTANA PROCESADA:" .$ventanaProcesadaRef."<br/>";
                if($ventanaProcesadaRef == 1){
                  //echo "ENTRA A CONTINUE <br/>";
                  continue;
                }else{
                  //echo "ENTRA A ELSE Y CAMBIA VALOR PARA VENTANA <br/>";
                  $ventanaProcesadaRef = 1;
                }
              }else{
                if($tipoElemento == "Muro" || $tipoElemento == "Puerta"){
                  //echo "VALOR MURO PROCESADO:" .$muroPuertaProcesadaRef."<br/>";
                  if($muroPuertaProcesadaRef == 1){
                    //echo "ENTRA A CONTINUE <br/>";
                    continue;
                  }else{
                    //echo "ENTRA A ELSE Y CAMBIA VALOR PARA MURO <br/>";
                    $muroPuertaProcesadaRef = 1;
                  }
                } 
              }
            } 

            echo "Tipo Elemento: " . $tipoElemento . "<br/>";
            echo "K Total: " . $kTotal . "<br/>";
            echo "Masivo o Ligero: " . $masivoLigero. "<br/>";
            echo "Tipo Sombra " . $tipoSombra . "<br/>";

            if($norma == "2011"){
              if($tipoElemento == "Ventana"){
                $fraccionComponente = 0.1;
              }else{
                if($tipoElemento == "Tragaluz"){
                  $fraccionComponente = 0;
                }else{
                  if($tipoElemento == "Techo"){
                    $fraccionComponente = 1.0;
                  }else{
                    $fraccionComponente = 0.9;
                  }
                }
              }
            }else{
              if($tipoElemento == "Ventana"){
                $fraccionComponente = 0.4;
              }else{
                if($tipoElemento == "Tragaluz"){
                  $fraccionComponente = 0.05;
                }else{
                  if($tipoElemento == "Techo"){
                    $fraccionComponente = 0.95;
                  }else{
                    $fraccionComponente = 0.6;
                  }
                }
              }
            }

            //De acuerdo al tipo de sombra, sacamos los datos del factor1 y factor2
            if($tipoElemento == "Ventana"){
              $se = 0;
              $query = "";

              if($tipoSombra == "1"){
                $se = 1;
              }
              if($tipoSombra == "2"){
                //Ocupar dirección, latitud y factor1 (L/H)
                $columna = "";
                $factor1 = (float)$lVoladoMas / (float)$hVoladoMas;
                echo "L: ". $lVoladoMas ."<br/>";
                echo "H: ". $hVoladoMas ."<br/>";
                echo "L/H: " . (float)$factor1 ."<br/>";

                if($orientacion == "Norte")
                  $columna = "norte";
                if($orientacion == "Este" || $orientacion == "Oeste")
                  $columna = "esteOeste";
                if($orientacion == "Sur")
                  $columna = "sur";

                $latitudNum = (float)$latitud;  
                
                if($norma == "2011"){
                  if($latitudNum <= 33.0 && $latitudNum >= 23.0)
                    $columna .= "23a33";
                  if($latitudNum < 23.0 && $latitudNum >= 14.0)
                    $columna .= "14a23";  
                }else{
                  if($latitudNum <= 33.0 && $latitudNum >= 28.0)
                    $columna .= "28a33";
                  if($latitudNum < 28.0 && $latitudNum >= 14.0)
                    $columna .= "14a28";  
                }

                if($norma == "2011")
                  $query = "SELECT " . $columna . " FROM tabla_2_2011 WHERE lH >= " . $factor1 . " ORDER BY lH ASC";
                else
                  $query = "SELECT " . $columna . " FROM tabla_2_2001 WHERE lH >= " . $factor1 . " ORDER BY lH ASC";
                $resultSombreado = mysql_query($query,$link);
                if($resultSombreado === FALSE){
                  $resultado["response"] = Constantes::ERROR;
                }else{
                  $totalUsu = mysql_num_rows($resultSombreado);
                  if($totalUsu > 0){
                    while($info = mysql_fetch_assoc($resultSombreado)){
                        $se = $info[$columna];
                        break;
                    }
                  }else{
                    $resultado["response"] = Constantes::ERROR;
                  }
                }    
              }else{
                if($tipoSombra == "3"){
                  //Ocupar dirección, latitud, factor1 (L/H) y factor2 (W/H)
                  if($norma == "2011")
                    $tabla = "tabla_3_2011_";
                  else
                    $tabla = "tabla_3_2001_";
                  $columna = "wH_";
                  $columna2 = "wH_";
                  $factor1 = (float)$lVoladoLimite / (float)$hVoladoLimite;
                  $factor2 = (float)$wVoladoLimite / (float)$hVoladoLimite;

                  if($orientacion == "Norte")
                    $tabla .= "Norte_";
                  if($orientacion == "Este" || $orientacion == "Oeste")
                    $tabla .= "EsteOeste_";
                  if($orientacion == "Sur")
                    $tabla .= "Sur_";

                  $latitudNum = (float)$latitud; 
                  if($latitudNum >= 14.0 && $latitudNum <= 19.0)
                    $tabla .= "1";
                  if($latitudNum > 19.0 & $latitudNum <= 23.0)
                    $tabla .= "2";
                  if($latitudNum > 23.0 && $latitudNum <= 28.0)
                    $tabla .= "3";
                  if($latitudNum > 28.0 && $latitudNum <= 32.0)
                    $tabla .= "4";

                  $factor2SinRedondeo = $factor2;  
                  $factor2 = round($factor2);
                  
                  if($factor2 <= 0.5){
                    $columna .= "0.5";
                  }
                  if($factor2 > 0.5 && $factor2 <= 1.0){
                    $columna .= "0.5";
                    $columna2 .= "1";
                  }
                  if($factor2 > 1.0 && $factor2 <= 2.0){
                    $columna .= "1";
                    $columna2 .= "2";
                  }
                  if($factor2 > 2.0 && $factor2 <= 4.0){
                    $columna .= "2";  
                    $columna2 .= "4";  
                  }
                  if($factor2 > 4.0 && $factor2 <= 6.0){
                    $columna .= "4";
                    $columna2 .= "6";
                  }
                  if($factor2 > 6.0 && $factor2 <= 8.0){
                    $columna .= "6";
                    $columna2 .= "8";
                  }
                  if($factor2 > 8.0){
                    $columna .= "8";
                  }

                  if($columna2 == "wH_"){

                    /** Sin factor de corrección **/

                    $query = "SELECT " . $columna . " FROM " . $tabla . " WHERE lH >= ". $factor1 . " ORDER BY lH ASC";

                    /*echo "COLUMNA: " . $columna ."<br/>";
                    echo "QUERY SOMBREADO 3: " . $query;*/

                    $resultSombreado = mysql_query($query,$link);
                    if($resultSombreado === FALSE){
                      $resultado["response"] = Constantes::ERROR;
                    }else{
                      $totalUsu = mysql_num_rows($resultSombreado);
                      if($totalUsu > 0){
                        while($info = mysql_fetch_assoc($resultSombreado)){
                            $se = $info[$columna];
                            break;
                        }
                      }else{
                        $resultado["response"] = Constantes::ERROR;
                      }
                    }   
                  }else{

                     /** Se aplica el factor de corrección **/
                    
                     if($factor1 >= 0.0 && $factor1 <= 0.10){
                      $factor1XN = 0.0;
                      $factor1XN1 = 0.10;
                    }
                    if($factor1 > 0.10 && $factor1 <= 0.20){
                      $factor1XN = 0.10;
                      $factor1XN1 = 0.20;
                    }
                    if($factor1 > 0.20 && $factor1 <= 0.30){
                      $factor1XN = 0.20;
                      $factor1XN1 = 0.30;
                    }
                    if($factor1 > 0.30 && $factor1 <= 0.40){
                      $factor1XN = 0.30;
                      $factor1XN1 = 0.40;
                    }
                    if($factor1 > 0.40 && $factor1 <= 0.50){
                      $factor1XN = 0.40;
                      $factor1XN1 = 0.50;
                    }
                    if($factor1 > 0.50 && $factor1 <= 0.60){
                      $factor1XN = 0.50;
                      $factor1XN1 = 0.60;
                    }
                    if($factor1 > 0.60 && $factor1 <= 0.70){
                      $factor1XN = 0.60;
                      $factor1XN1 = 0.70;
                    }
                    if($factor1 > 0.70 && $factor1 <= 0.80){
                      $factor1XN = 0.70;
                      $factor1XN1 = 0.80;
                    }
                    if($factor1 > 0.80 && $factor1 <= 1.0){
                      $factor1XN = 0.80;
                      $factor1XN1 = 1.0;
                    }
                    if($factor1 > 1.0){
                      $factor1XN = 1.0;
                      $factor1XN1 = 1.2;
                    }

                    $query = "SELECT * FROM " . $tabla . " WHERE lH >= ". $factor1XN ." AND lH <= ". $factor1XN1 ." ORDER BY lH ASC";

                    /*echo "QUERY CORRECCION: " . $query. "<br/>";*/

                    $pos = 0;
                    $resultSombreado = mysql_query($query,$link);
                    if($resultSombreado === FALSE){
                      $resultado["response"] = Constantes::ERROR;
                    }else{
                      $totalUsu = mysql_num_rows($resultSombreado);
                      if($totalUsu > 0){
                        while($info = mysql_fetch_assoc($resultSombreado)){
                            
                            $xn[$pos] = $info[$columna];
                            $xn1[$pos] = $info[$columna2];
                            /*echo "XN: " .$xn[$pos]."<br/>";
                            echo "XN1: " .$xn1[$pos]."<br/>";*/
                            $pos++;
                        }
                      }else{
                        $resultado["response"] = Constantes::ERROR;
                      }
                    }

                     //Aplicar funcion para obtener el valor de se

                     $se = calculoFactorCorreccion($factor1, $factor2SinRedondeo, $columna, $columna2, $factor1XN, $factor1XN1, $xn[0], $xn1[0], $xn[1], $xn1[1]);
                    
                     /*echo "SE SOMBREADO 3: " . $se . "<br/>";*/
                  }

                }else{
                  if($tipoSombra == "4"){
                    //Ocupar dirección, latitud, factor1 (P/E) y factor2 (W/E)
                    if($norma == "2011")
                      $tabla = "tabla_4_2011_";
                    else
                      $tabla = "tabla_4_2001_";
                    $columna = "wE_";
                    $columna2 = "wE_";
                    $factor1 = (float)$pRemetida / (float)$eRemetida;
                    $factor2 = (float)$wRemetida / (float)$eRemetida;

                    if($orientacion == "Norte")
                      $tabla .= "Norte_";
                    if($orientacion == "Este" || $orientacion == "Oeste")
                      $tabla .= "EsteOeste_";
                    if($orientacion == "Sur")
                      $tabla .= "Sur_";

                    $latitudNum = (float)$latitud; 
                    if($latitudNum >= 14.0 && $latitudNum <= 19.0)
                      $tabla .= "1";
                    if($latitudNum > 19.0 & $latitudNum <= 23.0)
                      $tabla .= "2";
                    if($latitudNum > 23.0 && $latitudNum <= 28.0)
                      $tabla .= "3";
                    if($latitudNum > 28.0 && $latitudNum <= 32.0)
                      $tabla .= "4";

                    $factor2SinRedondeo = $factor2;  
                    $factor2 = round($factor2);
                    
                    if($factor2 <= 0.5){
                      $columna .= "0.5";
                    }
                    if($factor2 > 0.5 && $factor2 <= 1.0){
                      $columna .= "0.5";
                      $columna2 .= "1";
                    }
                    if($factor2 > 1.0 && $factor2 <= 2.0){
                      $columna .= "1";
                      $columna2 .= "2";
                    }
                    if($factor2 > 2.0 && $factor2 <= 4.0){
                      $columna .= "2";  
                      $columna2 .= "4";  
                    }
                    if($factor2 > 4.0 && $factor2 <= 6.0){
                      $columna .= "4";
                      $columna2 .= "6";
                    }
                    if($factor2 > 6.0 && $factor2 <= 8.0){
                      $columna .= "6";
                      $columna2 .= "8";
                    }
                    if($factor2 > 8.0){
                      $columna .= "8";
                    }

                    if($columna2 == "wE_"){

                      /** Sin factor de corrección **/

                      $query = "SELECT " . $columna . " FROM " . $tabla . " WHERE pE >= ". $factor1 . " ORDER BY pE ASC";
                      
                      $resultSombreado = mysql_query($query,$link);
                      if($resultSombreado === FALSE){
                        $resultado["response"] = Constantes::ERROR;
                      }else{
                        $totalUsu = mysql_num_rows($resultSombreado);
                        if($totalUsu > 0){
                          while($info = mysql_fetch_assoc($resultSombreado)){
                              $se = $info[$columna];
                              break;
                          }
                        }else{
                          $resultado["response"] = Constantes::ERROR;
                        }
                      }   
                    }else{
                      /** Se aplica el factor de corrección **/
                                          
                      if($factor1 >= 0.0 && $factor1 <= 0.10){
                        $factor1XN = 0.0;
                        $factor1XN1 = 0.10;
                      }
                      if($factor1 > 0.10 && $factor1 <= 0.20){
                        $factor1XN = 0.10;
                        $factor1XN1 = 0.20;
                      }
                      if($factor1 > 0.20 && $factor1 <= 0.30){
                        $factor1XN = 0.20;
                        $factor1XN1 = 0.30;
                      }
                      if($factor1 > 0.30 && $factor1 <= 0.40){
                        $factor1XN = 0.30;
                        $factor1XN1 = 0.40;
                      }
                      if($factor1 > 0.40 && $factor1 <= 0.50){
                        $factor1XN = 0.40;
                        $factor1XN1 = 0.50;
                      }
                      if($factor1 > 0.50 && $factor1 <= 0.60){
                        $factor1XN = 0.50;
                        $factor1XN1 = 0.60;
                      }
                      if($factor1 > 0.60 && $factor1 <= 0.70){
                        $factor1XN = 0.60;
                        $factor1XN1 = 0.70;
                      }
                      if($factor1 > 0.70 && $factor1 <= 0.80){
                        $factor1XN = 0.70;
                        $factor1XN1 = 0.80;
                      }
                      if($factor1 > 0.80 && $factor1 <= 1.0){
                        $factor1XN = 0.80;
                        $factor1XN1 = 1.0;
                      }
                      if($factor1 > 1.0){
                        $factor1XN = 1.0;
                        $factor1XN1 = 1.2;
                      }

                      $query = "SELECT * FROM " . $tabla . " WHERE pE >= ". $factor1XN ." AND pE <= ". $factor1XN1 ." ORDER BY pE ASC";

                      /*echo "QUERY CORRECCION: " . $query. "<br/>";*/

                      $pos = 0;
                      $resultSombreado = mysql_query($query,$link);
                      if($resultSombreado === FALSE){
                        $resultado["response"] = Constantes::ERROR;
                      }else{
                        $totalUsu = mysql_num_rows($resultSombreado);
                        if($totalUsu > 0){
                          while($info = mysql_fetch_assoc($resultSombreado)){
                              
                              $xn[$pos] = $info[$columna];
                              $xn1[$pos] = $info[$columna2];
                              /*echo "XN: " .$xn[$pos]."<br/>";
                              echo "XN1: " .$xn1[$pos]."<br/>";*/
                              $pos++;
                          }
                        }else{
                          $resultado["response"] = Constantes::ERROR;
                        }
                      }

                      //Aplicar funcion para obtener el valor de se

                      $se = calculoFactorCorreccion($factor1, $factor2SinRedondeo, $columna, $columna2, $factor1XN, $factor1XN1, $xn[0], $xn1[0], $xn[1], $xn1[1]);

                      /*echo "SE SOMBREADO 4: " . $se . "<br/>";*/

                    }
                  }else{
                    if($tipoSombra == "5"){
                      //Ocupar dirección, latitud, factor1 (L/W)
                      $columna = "";
                      $factor1 = (float)$lParteluces / (float)$wParteluces;

                      if($orientacion == "Norte")
                        $columna = "norte";
                      if($orientacion == "Este" || $orientacion == "Oeste")
                        $columna = "esteOeste";
                      if($orientacion == "Sur")
                        $columna = "sur";

                      $latitudNum = (float)$latitud;  
                      if($latitudNum >= 14.0 && $latitudNum <= 19.0)
                        $columna .= "14a19";
                      if($latitudNum > 19.0 && $latitudNum <= 23.0)
                        $columna .= "19a23";
                      if($latitudNum > 23.0 && $latitudNum <= 28.0)
                        $columna .= "23a28";    
                      if($latitudNum > 28.0 && $latitudNum <=32)
                        $columna .= "28a32";

                      if($norma == "2011")
                        $query = "SELECT " . $columna . " FROM tabla_5_2011 WHERE lW >= " . $factor1 . " ORDER BY lW ASC";
                      else
                        $query = "SELECT " . $columna . " FROM tabla_5_2001 WHERE lW >= " . $factor1 . " ORDER BY lW ASC";

                      //Del resultado tomar solo el primer elemento
                      $resultSombreado = mysql_query($query,$link);
                      if($resultSombreado === FALSE){
                        $resultado["response"] = Constantes::ERROR;
                      }else{
                        $totalUsu = mysql_num_rows($resultSombreado);
                        if($totalUsu > 0){
                          while($info = mysql_fetch_assoc($resultSombreado)){
                              $se = $info[$columna];
                              break;
                          }
                        }else{
                          $resultado["response"] = Constantes::ERROR;
                        }
                      }   
                    }else{
                      $se = 1;
                    }
                  }
                }
              }  
            }

            //Armamos la consulta para obtener la temperatura equivalente, la temperatura interior, 
            //el factor de ganancia y la K de referencia

            $query = "SELECT tInterior, ";

            if($orientacion == "Techo"){
              $query .= "teTecho, ";
              $columnaTE = "teTecho";
            }else{
              if($orientacion == "TechoI"){
                $query .= "sInterior, ";
                $columnaTE = "sInterior";
              }else{
                if($tipoElemento == "Tragaluz"){
                  $query .= "teTragaluz, ";
                  $columnaTE = "teTragaluz";
                }else{
                  if($tipoElemento == "Muro"){
                    if($masivoLigero == "Masivo"){
                      $query .= "teMasivo";
                      $columnaTE = "teMasivo";
                    }else{
                      $query .= "teLigero";
                      $columnaTE = "teLigero";
                    }
                  }else{
                    $query .= "teVentana";
                    $columnaTE = "teVentana";
                  }
                  $query .= $orientacion.", ";
                  $columnaTE .= $orientacion;
                }
              }
            }

            $nivelesEdificio = (int)$numNivelesEdificio;
            if($norma == "2011"){
              if($nivelesEdificio <= 3){
                $query .="kMenor3 ";
                $columnaK = "kMenor3";
                echo "Niveles edificio menor o igual a 3<br/>";
              }else{
                echo "Niveles edificio mayor a 3<br/>";
                if($orientacion == "Techo" || $orientacion == "TechoI"){
                  $query .= "kMayor3Techo";
                  $columnaK = "kMayor3Techo";
                }else{
                  $query .= "kMayor3Muro";
                  $columnaK = "kMayor3Muro";
                }
              }
            }else{
              $query .= "kMayor3Muro";
              $columnaK = "kMayor3Muro";
            }

            if($tipoElemento == "Tragaluz"){
              $query .= ", fgTragaluz ";
              $columnaFG = "fgTragaluz";
            }else{
              if($tipoElemento == "Ventana"){
                $query .= ", fgTragaluz".$orientacion." ";
                $columnaFG = "fgTragaluz".$orientacion;
              }
            }

            $query .= " FROM tabla_1_".$norma." WHERE latitud = '".$latitud."'";

           //echo "QUERY: ".$query."<br/>";

            $resultTabla1 = mysql_query($query,$link); 
            if($resultTabla1 === FALSE){
              $resultado["response"] = Constantes::ERROR;
            }else{
              $totalUsu = mysql_num_rows($resultTabla1);
              if($totalUsu > 0){
                $i=0;
                while($info = mysql_fetch_assoc($resultTabla1)){
                  $te = $info[$columnaTE];
                  $ti = $info["tInterior"];
                  $fg = $info[$columnaFG];
                  if($tipoElemento == "Ventana"){
                    $kProy = 5.319;
                  }else{
                    if($tipoElemento == "Tragaluz"){
                      $kProy = 5.952;
                    }else{
                      $kProy = $info[$columnaK];
                    }
                  }
                }
              }
            }

            //Se aplican los cálculos para la ganancia de calor (referencia y proyectado)

            echo "KTOTAL: ".$kTotal."<br/>";
            echo "AREA TOTAL ORIENTACION: ".$areaTotalOrientacion."<br/>";
            echo "FRACCION COMPONENTE: ".$fraccionComponente."<br/>";
            echo "TE: ".$te."<br/>";
            echo "TI: ".$ti."<br/>";
            echo "COLUMNA K: ".$columnaK."<br/>";
            echo "KPROYECTADO: ".$kProy."<br/><br/>";
         
            if($tipoCalculo != "Referencia"){
              //Ganancia de calor proyectado
              $res = ((float)$kTotal * (float)$areaTotalOrientacion * ((float)$te - (float)$ti));
              $resultadosCalculos[0] += $res; 
              echo "Calculo ganancia de calor por conducción proyectado: " .$kTotal." * ".$areaTotalOrientacion." * ( ".$te." - ".$ti." ) = ".$res."<br/><br/>";
              echo "Acumulado sumatoria ganancia de calor por conducción proyectado : " .$resultadosCalculos[0]."<br/><br/>";
            }else{
              //Ganancia de calor referencia
              $res = ((float)$kProy * (float)$areaTotalOrientacion * (float)$fraccionComponente * ((float)$te - (float)$ti));
              $resultadosCalculos[1] += $res;
              echo "Calculo ganancia de calor por conducción referencia: " .$kProy." * ".$areaTotalOrientacion." * ".$fraccionComponente." * ( ".$te." - ".$ti." ) = ".$res."<br/><br/>";
              echo "Acumulado sumatoria ganancia de calor por conducción referencia: " .$resultadosCalculos[1]."<br/><br/>";
            }

            //Se aplican los cálculos para la ganancia por radiación

            if($tipoElemento == "Tragaluz" || $tipoElemento == "Ventana"){
              /*echo "CS: ".$cs."<br/>";
              echo "FG: ".$fg."<br/>";
              echo "SE: ".$se."<br/>";*/
              if($tipoCalculo != "Referencia"){
                //Ganancia de calor radiación proyectado
                $res = (float)$areaTotalOrientacion * (float)$cs * (float)$fg * (float)$se;
                $resultadosCalculos[2] += $res;
                echo "Calculo ganancia de calor por radiación proyectado: ".$areaTotalOrientacion." * ".$cs." * ".$fg." * ".$se." = ".$res."<br/><br/>";
                echo "Acumulado sumatoria ganancia de calor por radiación proyectado: " .$resultadosCalculos[2]."<br/><br/>";
              }else{
                //Ganancia de calor radiación referencia
                $res = (float)$areaTotalOrientacion * (float)$cs * (float)$fraccionComponente * (float)$fg;
                $resultadosCalculos[3] += $res;
                echo "Calculo ganancia de calor por radiación referencia: ".$areaTotalOrientacion." * ".$cs." * ".$fraccionComponente." * ".$fg." = ".$res."<br/><br/>";
                echo "Acumulado ganancia de calor por radiación referencia: " .$resultadosCalculos[3]."<br/><br/>";
              }
            }

            /*echo "RESULTADOS CALCULOS [0]: ".$resultadosCalculos[0];
            echo "RESULTADOS CALCULOS [1]: ".$resultadosCalculos[1];
            echo "RESULTADOS CALCULOS [2]: ".$resultadosCalculos[2];
            echo "RESULTADOS CALCULOS [3]: ".$resultadosCalculos[3];*/

            $i++;
        }
    }
    return $resultadosCalculos;
  }

  function calculoFactorCorreccion($y, $x, $xn, $xn1, $yn, $yn1, $a, $b, $c, $d){
    
    $xn = str_replace('wH_', '', $xn);
    $xn1 = str_replace('wH_', '', $xn1);
    $xn = str_replace('wE_', '', $xn);
    $xn1 = str_replace('wE_', '', $xn1);

    /*echo "Y: " . $y . "<br/>";
    echo "X: " . $x . "<br/>";
    echo "XN: " . $xn . "<br/>";
    echo "XN1: " . $xn1 . "<br/>";
    echo "YN: " . $yn . "<br/>";
    echo "YN1: " . $yn1 . "<br/>";
    echo "A: " . $a . "<br/>";
    echo "B: " . $b . "<br/>";
    echo "C: " . $c . "<br/>";
    echo "D: " . $d . "<br/>";*/
    
    $fx = (float)(($x - $xn) / ($xn1 - $xn));
    $fy = (float)(($y - $yn) / ($yn1 - $yn));
    $parte1 = (float)($fx * $fy * ($d - $c - $b + $a));
    $parte2 = (float)($fx * ($b - $a));
    $parte3 = (float)($fy * ($c - $a));

    /*echo "PARTE 1: " . $parte1 . "<br/>";
    echo "PARTE 2: " . $parte2 . "<br/>";
    echo "PARTE 3: " . $parte3 . "<br/>";*/

    $res = (float) ($parte1 + $parte2 + $parte3 + $a);

    return $res;
  }

?>
