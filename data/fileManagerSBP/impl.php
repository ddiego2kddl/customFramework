<?php
	$paqueteriaRoute = 	$path_route."/impl/src/main/java".$paqueteria_route;
	$paqueteriaRoute= (string)$paqueteriaRoute;

	if (!file_exists($paqueteriaRoute)) {
		mkdir($paqueteriaRoute,0777,true);
	}

	include 'impl/pomXML.php';

	//***********************************//
	//atencion, me muevo a esta carpeta, //
	//***********************************//
	chdir($paqueteriaRoute);


	//UTILIDADES
	if (!file_exists("flow/utilidades")) {
		mkdir("flow/utilidades",0777,true);
	}
		//Utilidades
		include 'impl/utilidades.php';
		//Constantes
		include 'impl/constantes.php';


	//INFO
	if (!file_exists("flow/info")) {
		mkdir("flow/info",0777,true);
	}
		//infos
		include 'impl/infos.php';

	//DTO
	if (!file_exists("flow/dto")) {
		mkdir("flow/dto",0777,true);
	}
		//beanauxiliares
		include 'impl/beanAuxiliares.php';
		//views
		include 'impl/views.php'; 
		//models
		include 'impl/models.php';



	//VIEWASSEMBLER
	if (!file_exists("flow/assemble")) {
		mkdir("flow/assemble",0777,true);
	}
		//viewAssembler
		include 'impl/viewAsembler.php';


	//ACTION
		include 'impl/action.php';

	//DAOS
	if (!file_exists("dao/dto")) {
		mkdir("dao/dto",0777,true);
	}
		//dao
		include 'impl/dao.php';
		//daoIMPL
		include 'impl/daoIMPL.php';
		//daoINDTO
		include 'impl/daoINDTO.php';
		//daoOUTDTO
		include 'impl/daoOUTDTO.php';



	//VUELVO AL DIRECTORIO ORIGINAL PARA SEGUIR DESPLAZANDOME
	chdir($originPath);

	$paqueteriaSrcMain = $path_route."/impl/src/main/";
	$paqueteriaSrcMain = (string)$paqueteriaSrcMain;

	if (!file_exists($paqueteriaSrcMain)) {
		mkdir($paqueteriaSrcMain,0777,true);
	}

	chdir($paqueteriaSrcMain);

	//RESOURCES
	if (!file_exists("resources/META-INF/spring")) {
		mkdir("resources/META-INF/spring",0777,true);
	}
		include 'impl/resources/comunes.php';
		include 'impl/resources/appContext.php';

	//FLOW
	if (!file_exists("resources/META-INF/views/".$paqueteria_flow_route)) {
		mkdir("resources/META-INF/views/".$paqueteria_flow_route,0777,true);
	}	
		include 'impl/resources/flow.php';


	//VUELVO AL DIRECTORIO ORIGINAL
	chdir($originPath);

	
?>