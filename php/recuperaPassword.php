<?php

  require("conexion.php");
	require("constantes.php");
	require("cors.php");
  require("utils.php");

  $data = json_decode(file_get_contents("php://input"));
  $correoElectronico = mysql_real_escape_string($data->correoRecuperarPassword);

	$query = "SELECT usuarioUsuario, passwordUsuario FROM usuario WHERE usuarioUsuario = '".$correoElectronico."'";

	$result = mysql_query($query,$link);

	if($result === FALSE){
		$resultado["response"] = Constantes::ERROR_SELECCION_NO_VALIDA;
	}else{
		$totalFilas = mysql_num_rows($result);
		if($totalFilas > 0){
        $info = mysql_fetch_assoc($result);

        $anioActual = date("Y");

		    $para = $correoElectronico;
        $titulo = 'Recuperación de Password de BemTec';

        $mensaje = '<html>'.
        '<head></head>'.
        '<body><h3>Recuperación de contraseña en BemTec</h3>'.
        '<b>Password: </b>'.
        $info["passwordUsuario"].
        '<br/><br/>'.
        'Muchas Gracias'.
        '<br/><br/>'.
        '<h4>BemTec '.$anioActual.'.</h4>'.
        '</body>'.
        '</html>';

        $cabeceras = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $cabeceras .= 'From: BemTec<no-reply@bemtec.mx>';
        $enviado = mail($para, $titulo, $mensaje, $cabeceras);

        $resultado["response"] = Constantes::EXITO;

		}else{
      $resultado["response"] = Constantes::ERROR;
    }

	}

echo json_encode($resultado);

?>
