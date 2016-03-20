<?php

echo getcwd();
	$paqueteriaTestRoute = 	$path_route."/impl/src/test/java".$paqueteria_route;
	$paqueteriaTestRoute= (string)$paqueteriaTestRoute;

	if (!file_exists($paqueteriaTestRoute)) {
		mkdir($paqueteriaTestRoute,0777,true);
	}

	//***********************************//
	//atencion, me muevo a esta carpeta, //
	//***********************************//
	chdir($paqueteriaTestRoute);
echo getcwd();

	//UTILIDADES
	if (!file_exists("flow/utilidades")) {
		mkdir("flow/utilidades",0777,true);
	}
		//utilidades
		include 'test/abstrackDaoMock.php';
		include 'test/escenario.php';
		include 'test/ExcepcionesEscenario.php';


	
?>