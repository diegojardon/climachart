<?php

/*
*		Servicio REST para realizar logout de la plataforma bemtec.
*
* 	@author: Diego Jardón
* 	@creationDate: 27-Jan-2018
* 	@version: 1.0
*
*/

	session_start();
	session_destroy();

?>
