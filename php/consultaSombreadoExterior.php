<?php

/*
*		Consulta del Sombreado Exterior por cada ventana en el cálculo de ganancia de calor por radiación.
*
* 	@author: Diego Jardón
* 	@creationDate: 2-Junio-2018
* 	@version: 1.0
*
*/

  

	require("conexion.php");
  require("constantes.php");

  obtieneSombreadoExterior("020", "3", "Este", "19.34", 0.76, 2.34);
  
  /**
   * @param norma -- Indica la norma, puede tomar los valores 008 o 020
   * @param tipoSombreado -- Indica el tipo de sombreado. Puede tomar los valores:
   *  1 -- Sin sombreado
   *  2 -- Volado más alla de los límites de la ventana
   *  3 -- Volado al límite de la ventana
   *  4 -- Ventana remetida
   *  5 -- Parteluces
   * @param direccion -- Indica el tipo de direccion. Puede tomar los valores 
   *  Norte, Sur, Este, Oeste, Techo y TechoI
   * @param latitud -- Indica latitud del edificio
   * @param factor1 -- Es el valor del factor de las filas para seleccionar el SE (si aplica)
   * @param factor2 -- Es el valor del factor de las columnas para seleccionar el SE (si aplica)
   */
  function obtieneSombreadoExterior($norma, $tipoSombreado, $direccion, $latitud, $factor1, $factor2){

    $se = 0;
    $query = "";

    if($norma == "020"){

      if($tipoSombreado == "1"){
        $se = 1;
      }
      if($tipoSombreado == "2"){
        //Ocupar dirección, latitud y factor1 (L/H)
        $columna = "";

        if($direccion == "Norte")
          $columna = "norte";
        if($direccion == "Este" || $direccion == "Oeste")
          $columna = "esteOeste";
        if($direccion == "Sur")
          $columna = "sur";

        $latitudNum = (float)$latitud;  
        if($latitudNum <= 33.0 && $latitudNum >= 23.0)
          $columna .= "23a33";
        if($latitudNum < 23.0 && $latitudNum >= 14.0)
          $columna .= "14a23";  

        $query = "SELECT " . $columna . " FROM tabla_2_2011 WHERE lH >= " . $factor1 . " ORDER BY lH ASC";

        //Del resultado tomar solo el primer elemento
        $se = $query;
      }else{
        if($tipoSombreado == "3"){
          //Ocupar dirección, latitud, factor1 (L/H) y factor2 (W/H)
          $tabla = "tabla_3_2011_";
          $columna = "wH_";

          if($direccion == "Norte")
            $tabla .= "Norte_";
          if($direccion == "Este" || $direccion == "Oeste")
            $tabla .= "EsteOeste_";
          if($direccion == "Sur")
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

          $factor2 = round($factor2);
          
          if($factor2 <= 0.5)
            $columna .= "0.5";
          if($factor2 > 0.5 && $factor2 <= 1.0)
            $columna .= "1";
          if($factor2 > 1.0 && $factor2 <= 2.0)
            $columna .= "2";
          if($factor2 > 2.0 && $factor2 <= 4.0)
            $columna .= "4";  
          if($factor2 > 4.0 && $factor2 <= 6.0)
            $columna .= "6";
          if($factor2 > 6.0)
            $columna .= "8";

          $query = "SELECT " . $columna . " FROM " . $tabla . " WHERE lH >= ". $factor1 . " ORDER BY lH ASC";

          $se = $query;
        }else{
          if($tipoSombreado == "4"){
            //Ocupar dirección, latitud, factor1 (P/E) y factor2 (W/E)
            $tabla = "tabla_4_2011_";
            $columna = "wE_";

            if($direccion == "Norte")
              $tabla .= "Norte_";
            if($direccion == "Este" || $direccion == "Oeste")
              $tabla .= "EsteOeste_";
            if($direccion == "Sur")
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

            $factor2 = round($factor2);
            
            if($factor2 <= 0.5)
              $columna .= "0.5";
            if($factor2 > 0.5 && $factor2 <= 1.0)
              $columna .= "1";
            if($factor2 > 1.0 && $factor2 <= 2.0)
              $columna .= "2";
            if($factor2 > 2.0 && $factor2 <= 4.0)
              $columna .= "4";  
            if($factor2 > 4.0 && $factor2 <= 6.0)
              $columna .= "6";
            if($factor2 > 6.0)
              $columna .= "8";

            $query = "SELECT " . $columna . " FROM " . $tabla . " WHERE pE >= ". $factor1 . " ORDER BY pE ASC";
            
            $se = $query;
          }else{
            if($tipoSombreado == "5"){
              //Ocupar dirección, latitud, factor1 (L/W)
              $columna = "";

              if($direccion == "Norte")
                $columna = "norte";
              if($direccion == "Este" || $direccion == "Oeste")
                $columna = "esteOeste";
              if($direccion == "Sur")
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

              $query = "SELECT " . $columna . " FROM tabla_5_2011 WHERE lW >= " . $factor1 . " ORDER BY lW ASC";

              //Del resultado tomar solo el primer elemento
              $se = $query;
            }else{
              $se = 1;
            }
          }
        }
      }  
    }else{
      //Es la norma 008
    }
    echo $se;
  }

?>
