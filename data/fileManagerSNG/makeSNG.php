<?php
	$sng = json_decode (file_get_contents('php://input'));

	//me guardo la primera direccion de directorio para poder volver cuando lo necesite
	$originPath = getcwd();

	//raiz del proyecto mas nombre del mismo
	$path_route = "../../Projects/".$sng->name;
	if (!file_exists($path_route)) {
		mkdir($path_route,0777,true);
	}


	//path de la paqueteria, para agregar en /src/main/java y /src/test/java
	$paqueteria_routeArray = explode(".", $sng->paqueteria);
	$paqueteria_route = "";

	for ($i=0, $size=count($paqueteria_routeArray); $i<$size; $i++){
		$paqueteria_route=$paqueteria_route."/".$paqueteria_routeArray[$i];
	}

	//2 primeras letras de la dependencia
	$sng->siglasDependencia = substr($sng->dependencia,0,2);



	include 'writeBasicStruct.php';
	include 'writeMainResources.php';
	include 'writePaqueteria.php';
	include 'writeTestPaqueteria.php';


	//comprimir folder
	chdir('../../Projects/');
	exec('zip -r '.$sng->name.'.zip '.$sng->name);

?>