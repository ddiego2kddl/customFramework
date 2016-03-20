<?php


	$myfile = fopen("flow/utilidades/Constantes.java", "w") or die("Unable to open file!");


	$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.utilidades;

public class Constantes {

	public final static String COD_ERROR_00001089 = "00001089";	// ImposibleObtenerLiquidacionesRepartoMargenes
	public final static String COD_ERROR_CQ0078 = "CQ0078";	// ImposibleObtenerExpedientes

	public final static String COD_ERROR_GENERICO = "EG0001"; // Error Generico
	public final static String DES_ERROR_GENERICO = "OPERATIVA TEMPORALMENTE NO DISPONIBLE, CONTACTE CON SU OFICINA";
	public final static String DES_ERROR_00001089 = "No se han podido obtener las liquidaciones de reparto de márgenes";
	public final static String COBROS_EXPORTACION = "0";
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>