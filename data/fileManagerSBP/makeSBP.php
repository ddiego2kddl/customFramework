<?php
	$sbp = json_decode (file_get_contents('php://input'));

	//me guardo la primera direccion de directorio para poder volver cuando lo necesite
	$originPath = getcwd();

	//raiz del proyecto mas nombre del mismo
	$path_route = "../../Projects/flow-".$sbp->subproceso->name;
	if (!file_exists($path_route)) {
		mkdir($path_route,0777,true);
	}


	//path de la paqueteria
	$paqueteria_routeArray = explode(".", $sbp->subproceso->paqueteria);
	$paqueteria_route = "";
	$paqueteria_flow = "";
	$paqueteria_flow_route = "";

	for ($i=0, $size=count($paqueteria_routeArray); $i<$size; $i++){
		$paqueteria_route=$paqueteria_route."/".$paqueteria_routeArray[$i];
		if ($i>3){
			$paqueteria_flow.="$paqueteria_routeArray[$i]";
			$paqueteria_flow_route.="$paqueteria_routeArray[$i]";
			if ($i!=$size-1){
				$paqueteria_flow.=".";
				$paqueteria_flow_route.="/";
			}
		}
	}


	//pom padre
	include 'pomXML.php';

	//impl
	include 'impl.php';


	//tests
	include 'test.php';


	//comprimir folder
	//chdir('../../Projects/');
	//exec('zip -r '.$sng->name.'.zip '.$sng->name);

?>