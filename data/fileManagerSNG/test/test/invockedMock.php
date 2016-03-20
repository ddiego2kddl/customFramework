<?php
	
	$myfile = fopen("test/Midtr2invokeMock.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.test;

public interface Midtr2invokeMock {

	/**
	 * Se incluye el siguiente paso en la orquestacion de la prueba Este paso
	 * llama a Midtr2Invoke y devuelve el mismo servicio que se ha incluido en
	 * el transformador de entrada con el metodo
	 * \'contextoSN.setCurrentItService\'
	 * 
	 * @return Midtr2invokeMock.
	 */
	public Midtr2invokeMock setSiguientePasoSinSalida();

	/**
	 * Se incluye el siguiente paso en la orquestacion de la prueba Este paso
	 * llama a Midtr2Invoke y devuelve el mismo servicio que se ha incluido en
	 * el transformador de entrada con el metodo
	 * \'contextoSN.setCurrentItService\' pero incluyendo los datos de salida que
	 * le inyectamos con el metodo \'nombreMetodoParaObtenerServicioIT\' que se
	 * encuentra en la clase \'claseParaObtenerServicioIT\' Este metodo ha de ser
	 * publico y recibir y devolver el servicioIT que queremos ejecutar.
	 * 
	 * @return Midtr2invokeMock.
	 */
	public Midtr2invokeMock setSiguientePaso(
			final Class<?> claseParaObtenerServicioIT,
			final String nombreMetodoParaObtenerServicioIT);

	/**
	 * Se incluye el siguiente paso en la orquestacion de la prueba que nos
	 * permite simular una ejecucion con error. Este paso llama a Midtr2Invoke y
	 * devuelve una WIException con el codigo de error \'errorCode\'.
	 * 
	 * @return Midtr2invokeMock.
	 */
	public Midtr2invokeMock setSiguientePasoLanzaWIException(
			final String errorCode);

	/**
	 * Se incluye el siguiente paso en la orquestacion de la prueba que nos
	 * permite simular una ejecucion con error. Este paso llama a Midtr2Invoke y
	 * devuelve una WIException con el codigo de error \'errorCode\' y el mensaje
	 * \'mensajeWIException\'.
	 * 
	 * @return Midtr2invokeMock.
	 */
	public Midtr2invokeMock setSiguientePasoLanzaWIException(
			final String errorCode, final String mensajeWIException);

}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>