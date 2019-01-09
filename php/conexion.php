<?php

	/*
	*		Conexión a Base de Datos para proyecto bemtec.
	*
	* 	@author: Diego Jardón
	* 	@creationDate: 27-Jan-2018
  * 	@version: 1.0
	*
	*/

	//$link = mysql_connect("localhost","root","") or die('No se pudo conectar a la BD');
  $link = mysql_connect("localhost","bemtec","b3mt3c") or die('No se pudo conectar a la BD');
	mysql_set_charset('utf8');
	mysql_select_db("bemtec",$link);
?>
