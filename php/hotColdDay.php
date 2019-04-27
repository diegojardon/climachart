<?php

/** 
 * @description -- Archivo que procesa el CSV de Climacharts para días fríos y calientes
 * @author -- Diego Jardon
 * @creationDate -- 27-Abril-2019
 * 
 * **/

require("constantes.php");
require("cors.php");

$data = json_decode(file_get_contents("php://input"));
$nombreArchivo = $data->{'nombreArchivo'};
$frioCaliente = $data->{'frioCaliente'};
$estado = $data->{'estado'};

$fila = 0;
$renglonInicialData = 0;

if (($gestor = fopen($nombreArchivo, "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        $numero = count($datos);
        if($renglonInicialData > 0){
            for ($c=0; $c < $numero; $c++) {
                if($estado == $datos[0] && $frioCaliente == $datos[7]){
                    switch($c){
                        case 0:
                            $resultado["info"]["city"] = $datos[$c];
                        break;
                        case 1:
                            $resultado["info"]["month"] = $datos[$c];
                        break;
                        case 2:
                            $resultado["info"]["points"][$fila]["day"] = $datos[$c];
                        break;
                        case 3:
                            $resultado["info"]["points"][$fila]["hour"] = $datos[$c];
                        break;
                        case 4:
                            $resultado["info"]["points"][$fila]["temperature"] = $datos[$c];
                        break;
                        case 5:
                            $resultado["info"]["points"][$fila]["humidity"] = $datos[$c];
                        break;
                        case 6:
                            $resultado["info"]["legend"] = $datos[$c];
                        break;
                        case 7:
                            $resultado["info"]["type"] = $datos[$c];                        
                        break;
                        case 8:
                            $resultado["info"]["points"][$fila]["color"] = $datos[$c];
                        break;
                    }
                    if($c==8)
                        $fila++;
                    //echo "Dato[".$c."]: ".$datos[$c] ."<br/>";
                }
            }
        }
        $renglonInicialData++;
    }
    fclose($gestor);
}

echo json_encode($resultado);

?>
