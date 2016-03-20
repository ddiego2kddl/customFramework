<?php
	
	$myfile = fopen("test/EscenarioSN.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.test;


public class EscenarioSN {

	private WIService servicioIT;
	private Class<?> claseParaObtenerServicioIT;
	private String nombreMetodoParaObtenerServicioIT;

	private WIException wiException;

	public Class<?> getClaseParaObtenerServicioIT() {
		return claseParaObtenerServicioIT;
	}

	public void setClaseParaObtenerServicioIT(
			final Class<?> claseParaObtenerServicioIT) {
		this.claseParaObtenerServicioIT = claseParaObtenerServicioIT;
	}

	public String getNombreMetodoParaObtenerServicioIT() {
		return nombreMetodoParaObtenerServicioIT;
	}

	public void setNombreMetodoParaObtenerServicioIT(
			final String nombreMetodoParaObtenerServicioIT) {
		this.nombreMetodoParaObtenerServicioIT = nombreMetodoParaObtenerServicioIT;
	}

	public WIService getServicioIT() {
		return servicioIT;
	}

	public void setServicioIT(final WIService servicioIT) {
		this.servicioIT = servicioIT;
	}

	public WIException getWiException() {
		return wiException;
	}

	public void setWiException(final String errorCode) {
		this.wiException = new WIException("", "", errorCode);
	}

	public void setWiException(final String errorCode,
			final String mensajeWiException) {
		this.wiException = new WIException(mensajeWiException, "", errorCode);
	}

	@Override
	public String toString() {
		return "EscenarioSN [servicioIT=" + servicioIT
				+ ", claseParaObtenerServicioIT=" + claseParaObtenerServicioIT
				+ ", nombreMetodoParaObtenerServicioIT="
				+ nombreMetodoParaObtenerServicioIT + ", wiException="
				+ wiException + "]";
	}

}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>