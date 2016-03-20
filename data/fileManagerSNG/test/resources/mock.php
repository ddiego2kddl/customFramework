<?php
	
	$myfile = fopen("resources/".$auxDependenciaName[0]."_Mock.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.resources;



public class '.$auxDependenciaName[0].'_Mock {

	public WIService sin_Rellamada_OK(final '.$sng->dependencia.' servicioIT)
			throws WIException {

		//servicioIT.setCodFinPaginacSNcofipa(ConstantesSNG_1.INDICADOR_NO_MAS);
		//servicioIT.setTablaCarterastabca1(componerTablaCarteras(false));

		return servicioIT;
	}
	
	public WIService con_Rellamada_OK(final '.$sng->dependencia.' servicioIT)
			throws WIException {

		//servicioIT.setCodFinPaginacSNcofipa(ConstantesSNG_1.INDICADOR_NO_MAS);
		//servicioIT.setTablaCarterastabca1(componerTablaCarteras(true));

		return servicioIT;
	}
	
	//CAMBIAR TIPO DEL VECTOR, Y SETEAR CON LOS CAMPOS CORRESPONDIENTES --> REFERENCIA TRANSFORM_OUT
/*	private Vector'.$sng->dependencia.'_TablaCarterastabca1 componerTablaCarteras(
			boolean rellamada) throws WIException {
		int numeroCarteras = 10;
		final Vector'.$sng->dependencia.'_TablaCarterastabca1 vectorGrupo = new Vector'.$sng->dependencia.'_TablaCarterastabca1();

		if (rellamada) {
			numeroCarteras = 15;
		}

		for (int i = 0; i < numeroCarteras; i++) {
			Struct'.$sng->dependencia.'_TablaCarterastabca1 structGrupo = new Struct'.$sng->dependencia.'_TablaCarterastabca1();

			structGrupo.setFechaVencimientofevto0BISA(Utilidades.crearFechaBisa("2015-02-01"));
			structGrupo.setTipoVencimientocotven(\'a\');
			structGrupo.setImpTotalOrdenesPagoimtoo1BISA(Utilidades.crearImporteBisa(2400,\'2\'));
			
			vectorGrupo.add(structGrupo);
		}

		return vectorGrupo;
	}*/
	
}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>