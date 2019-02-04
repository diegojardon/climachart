<?php

/** 
 * @description -- Archivo que procesa los CSV's de Climacharts y los transforma a Json
 * @author -- Diego Jardon
 * @creationDate -- 3-Febrero-2019
 * 
 * **/

require("constantes.php");
require("cors.php");

$data = json_decode(file_get_contents("php://input"));
$nombreArchivo = $data->{'nombreArchivo'};

$fila = 1;
$renglonInicialData = 5;

if($nombreArchivo == null)
    echo "PRUEBA";

if (($gestor = fopen($nombreArchivo, "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        $numero = count($datos);
        if($fila == 1){
            //Procesar información del estado, ubicación, etc.
        }else{
            for ($c=0; $c < $numero; $c++) {
                if($fila == 2){
                    if($datos[$c] != "month" && $datos[$c] != "")
                        $resultado["info"][$c-1]["name"] = $datos[$c];
                }else{
                    if($fila == 3){
                        if($datos[$c] != "" && strlen($datos[$c]) > 1)
                            $resultado["info"][$c-1]["name"] .= " (" .$datos[$c].")";
                    }else{
                        if($fila == 4){
                            if($datos[$c] != "" && strlen($datos[$c]) > 1)
                                $resultado["info"][$c-1]["units"] = $datos[$c];
                        }else{
                            if($c != 0){
                                if($datos[$c] != ""){
                                    $resultado["info"][$c-1]["data"][$fila - $renglonInicialData] = $datos[$c];
                                }
                            }
                        } 
                    }    
                }
            }
        }
        $fila++;
    }

    fclose($gestor);
}

echo json_encode($resultado);

?>
