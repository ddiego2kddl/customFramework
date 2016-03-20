<?php

	//lo siguiente es crear la ruta del test, y los includes necesarios
	$testPaqueteria_route = $path_route."/src/test/java".$paqueteria_route;
	if (!file_exists($testPaqueteria_route)) {
		mkdir($testPaqueteria_route,0777,true);
	}

	//***********************************//
	//atencion, me muevo a esta carpeta, //
	//***********************************//
	chdir($testPaqueteria_route);

	//obtengo nombre sin _INS de la dependencia para los mocks
	$auxDependenciaName = explode("_", $sng->dependencia);
	include 'test/testMain.php';


	//EXCEPTION
	if (!file_exists("exception")) {
		mkdir("exception",0777,true);
	}
	include 'test/exception/expectedMatcher.php';
	include 'test/exception/expectedException.php';


	//RESOURCES
	if (!file_exists("resources")) {
		mkdir("resources",0777,true);
	}
	include 'test/resources/mock.php';
	include 'test/resources/classMock.php';



	//TEST
	if (!file_exists("test")) {
		mkdir("test",0777,true);
	}
	include 'test/test/camelRoute.php';
	include 'test/test/escenarioSN.php';
	include 'test/test/invockedMock.php';
	include 'test/test/invockedMockImpl.php';
	include 'test/test/snTestHelper.php';
	include 'test/test/snTestHelperImpl.php';



	//**************************************//
	//atencion vuelvo a la carpeta original //
	//**************************************//
	chdir($originPath);
?>