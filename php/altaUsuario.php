<?php

/*
*		Alta de usuarios para la plataforma bemtec.
*
* 	@author: Diego Jardón
* 	@creationDate: 27-Jan-2018
* 	@version: 1.0
*
*/

  session_start();

	require("conexion.php");
	require("constantes.php");
	require("cors.php");
  require("utils.php");

	$data = json_decode(file_get_contents("php://input"));
	$usuarioUsuario = mysql_real_escape_string($data->usuarioUsuario);
  $passwordUsuario = mysql_real_escape_string($data->passwordUsuario);
	$institucionUsuario = mysql_real_escape_string($data->institucionUsuario);

	//Datos Usuario
	/*$usuarioUsuario = "dynosaur1218@hotmail.com";
	$passwordUsuario = "test";
	$institucionUsuario = "test";*/

 //Buscar si el nombre de usuario está libre

  $query = "SELECT * FROM usuario WHERE usuarioUsuario='".$usuarioUsuario."'";
	$result = mysql_query($query,$link);
	$totalFilas = mysql_num_rows($result);

  if($totalFilas == 0){

  	$query = "INSERT INTO usuario (`idUsuario`,`usuarioUsuario`,`passwordUsuario`,`institucionUsuario`,`estatusUsuario`)
				  VALUES (NULL, '$usuarioUsuario', '$passwordUsuario', '$institucionUsuario', 0)";

		$result = mysql_query($query);

		if($result === TRUE){

      $llave = encriptar($usuarioUsuario);

      $query = "INSERT INTO usuarioKey (`idUsuarioKey`,`keyUsuarioKey`)
            VALUES (NULL, '$llave')";
      $result = mysql_query($query,$link);

      //enviar correo electronico con url para finalizar el registro

      $urlRegistro = "http://www.bemtec.mx/bemtec/php/validaRegistro.php?id=" . $llave;
      $anioActual = date("Y");

      $para = $usuarioUsuario;
      $titulo = 'Estas por concluir tu registro en BemTec';

      $mensaje = '<html>'.
     '<head></head>'.
     '<body><h3>Registro en BemTec</h3><br/>'.
     '<p>Da clic en el siguiente enlace para finalizar tu registro: </p><br/>'.
     '<a href="'.$urlRegistro.'">'.$urlRegistro.'</a>'.
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
			$resultado["response"] = Constantes::ERROR_INSERCION_USUARIO_NO_VALIDA;
		}

	}else{
		$resultado["response"] = Constantes::ERROR_USUARIO_EXISTENTE;
	}

	echo json_encode($resultado);
?>
