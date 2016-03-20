<?php

	$javaMetaInf_route = $path_route."/src/main/java/META-INF";
	if (!file_exists($javaMetaInf_route)) {
		mkdir($javaMetaInf_route,0777,true);
	}

	
	////////////////////////////////////
	//MANIFEST.MF///////////////////////
	////////////////////////////////////

	$myfile = fopen($javaMetaInf_route."/MANIFEST.MF", "w") or die("Unable to open file!");

	$txt = 'Manifest-Version: 1.0
Class-Path: 
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);


	///////////////////////////////////
	// src/main/java/paqueteria////////
	///////////////////////////////////

	$mainPaqueteria_route = $path_route."/src/main/java".$paqueteria_route;
	if (!file_exists($mainPaqueteria_route)) {
		mkdir($mainPaqueteria_route,0777,true);
	}


	//***********************************//
	//atencion, me muevo a esta carpeta, //
	//***********************************//
	chdir($mainPaqueteria_route);

	include 'main/writeControlador.php';
	include 'main/writeTransformOut.php';
	include 'main/writeTransformIN.php';


	//escribir utilidades
	if (!file_exists("utilidades")) {
		mkdir("utilidades",0777,true);
	}
	include 'main/utilidades/abstract.php';
	include 'main/utilidades/constants.php';
	include 'main/utilidades/utilidades.php';

	//**************************************//
	//atencion vuelvo a la carpeta original //
	//**************************************//
	chdir($originPath);


?>