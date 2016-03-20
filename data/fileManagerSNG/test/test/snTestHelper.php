<?php
	
	$myfile = fopen("test/SNTestHelper.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.test;

import java.util.ArrayList;


public interface SNTestHelper {

	/**
	 * Se incluye el primer paso de la prueba. Este paso llama a Midtr2Invoke y
	 * devuelve el mismo servicio que se ha incluido en el transformador de
	 * entrada con el metodo \'contextoSN.setCurrentItService\'
	 * 
	 * @return Midtr2invokeMock.
	 */
	public Midtr2invokeMock setPrimerPasoSinSalida();

	/**
	 * Se incluye el primer paso de la prueba. Este paso llama a Midtr2Invoke y
	 * devuelve el mismo servicio que se ha incluido en el transformador de
	 * entrada con el metodo \'contextoSN.setCurrentItService\' pero incluyendo
	 * los datos de salida que le inyectamos con el metodo
	 * \'nombreMetodoParaObtenerServicioIT\' que se encuentra en la clase
	 * \'claseParaObtenerServicioIT\' Este metodo ha de ser publico y recibir y
	 * devolver el servicioIT que queremos ejecutar.
	 * 
	 * @return Midtr2invokeMock.
	 */
	public Midtr2invokeMock setPrimerPaso(
			final Class<?> claseParaObtenerServicioIT,
			final String nombreMetodoParaObtenerServicioIT);

	/**
	 * Se incluye el primer paso de la prueba que nos permite simular una
	 * ejecucion con error. Este paso llama a Midtr2Invoke y devuelve una
	 * WIException con el codigo de error \'errorCode\'.
	 * 
	 * @return Midtr2invokeMock.
	 */
	public Midtr2invokeMock setPrimerPasoLanzaWIException(final String errorCode);

	/**
	 * Se incluye el primer paso de la prueba que nos permite simular una
	 * ejecucion con error. Este paso llama a Midtr2Invoke y devuelve una
	 * WIException con el codigo de error \'errorCode\' y el mensaje
	 * \'mensajeWIException\'.
	 * 
	 * @return Midtr2invokeMock.
	 */
	public Midtr2invokeMock setPrimerPasoLanzaWIException(
			final String errorCode, final String mensajeWIException);

	/**
	 * Permite recuperar la cabecera del servicio de negocio para realizar
	 * cualquier modificacion de los datos de entrada al servicio. Estas
	 * modificaciones son las que se inyectan en el servicio antes de realizar
	 * la ejecucion de la prueba.
	 * 
	 * @return StructCabeceraServicioNegocio.
	 */
	public StructCabeceraServicioNegocio obtenerCabeceraServicioNegocio();

	/**
	 * Metodo que nos permite evaluar en un assert si se ha llamado a midtr
	 * tantas veces como se esperaba en la definicion del escenario de prueba.
	 * 
	 * @return boolean con valor true si efectivamente se han ejecutado o false
	 *         si el numero de pasos difiere.
	 */
	public boolean seHanEjecutadoTodosLosPasos();

	/**
	 * Es importante validar los campos de entrada de todos los IT\'s que se
	 * ejecutan en la orquestacion. Metodo que nos permite comprobar una
	 * ejecucion \'mock\' del servicio utilizando el contexto que utiliza los
	 * pasos definidos con el SNTestHelper para obtener los datos de respuesta
	 * de las ejecuciones a los distintos servicios. Estos pasos pueden ser con
	 * respuesta, sin respuesta y que lanzan una WIException.
	 * 
	 * @param validarCamposEntrada
	 *            boolean Parametro que permite activar la validacion de los
	 *            campos de entrada al servicio de negocio.
	 * @throws Exception
	 */
	public void ejecutarTest(final boolean validarCamposEntrada);

	/**
	 * Metodo que nos permite comprobar una ejecucion integrada del servicio
	 * utilizando el contexto y una ejecucion al Midtr2Invoke original.
	 */
	public void ejecutarTestIntegrado();

	/**
	 * Metodo que nos permite recuperar los pasos que se han definido en para la
	 * prueba unitaria.
	 */
	public ArrayList<EscenarioSN> obtenerPasos();

	/**
	 * Metodo que nos permite recuperar los pasos que se han ejecutado en la
	 * prueba unitaria. Estos pasos ya incluyen las transformaciones de entrada,
	 * los datos incluidos en los mock y las posibles excepciones que hayamos
	 * generado en la orquestación tras haberlas definido con los pasos
	 * correspondientes.
	 */
	public ArrayList<EscenarioSN> obtenerPasosEjecutados();

	public void incluirCabeceraSN(final WIService servicioNegocio);

}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>