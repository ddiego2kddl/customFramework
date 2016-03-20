<?php


	$myfile = fopen("flow/utilidades/Escenario.java", "w") or die("Unable to open file!");


	$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.utilidades;

import lombok.Data;

@Data
public class Escenario {

	private String identificadorEscenario = null;
	private String nombreMetodoParaObtenerOutDTO = null;
	private Object outDTO = null;
	private ExcepcionesEscenario excepcionesEscenario = new ExcepcionesEscenario();

}
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>