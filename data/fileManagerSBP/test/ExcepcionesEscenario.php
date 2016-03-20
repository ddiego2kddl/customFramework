<?php


	$myfile = fopen("flow/utilidades/ExcepcionesEscenario.java", "w") or die("Unable to open file!");


	$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.utilidades;

import lombok.AccessLevel;
import lombok.Getter;
import lombok.Setter;


public class ExcepcionesEscenario {

	@Getter(AccessLevel.PACKAGE)
	private ServiceException serviceException = null;

	@Getter(AccessLevel.PACKAGE)
	private final String errorCode = null;
	@Getter(AccessLevel.PACKAGE)
	private boolean controlarExcepcion = false;
	@Getter(AccessLevel.PACKAGE)
	@Setter(AccessLevel.PACKAGE)
	private ServiceException excepcionControlada = null;

	/**
	 * 
	 * @param ServiceException
	 *            ServiceException que vamos a lanzar
	 * @return ExcepcionesEscenario que nos permite incluir la excepcion que
	 *         esperamos recibir
	 */
	public ExcepcionesEscenario lanzaException(
			final ServiceException ServiceException) {
		this.serviceException = ServiceException;
		this.controlarExcepcion = false;

		return this;
	}

	/**
	 * 
	 * @param errorCode
	 *            codigo de error de la ServiceException que vamos a lanzar
	 * @return ExcepcionesEscenario que nos permite incluir la excepcion que
	 *         esperamos recibir
	 */
	public ExcepcionesEscenario lanzaException(final String errorCode) {
		this.serviceException = new ServiceException(errorCode, "");
		this.controlarExcepcion = false;

		return this;
	}

	/**
	 * 
	 * @param errorCode
	 *            codigo de error de la ServiceException que vamos a lanzar
	 * @param mensaje
	 *            mensaje de error
	 * @return ExcepcionesEscenario que nos permite incluir la excepcion que
	 *         esperamos recibir
	 */
	public ExcepcionesEscenario lanzaException(final String errorCode,
			final String mensaje) {
		this.serviceException = new ServiceException(errorCode, mensaje);
		this.controlarExcepcion = false;

		return this;
	}

	/**
	 * La excepcion se guarda en la ejecucion antes de ser lanzada por si fuese
	 * necesario realizar alguna comprobación posterior.
	 * 
	 * @param ServiceException
	 *            ServiceException que vamos a lanzar
	 * @return ExcepcionesEscenario que nos permite incluir la excepcion que
	 *         esperamos recibir
	 */
	public ExcepcionesEscenario lanzaExceptionControlada(
			final ServiceException ServiceException) {
		this.serviceException = ServiceException;
		this.controlarExcepcion = true;

		return this;
	}

	/**
	 * La excepcion se guarda en la ejecucion antes de ser lanzada por si fuese
	 * necesario realizar alguna comprobación posterior.
	 * 
	 * @param errorCode
	 *            codigo de error de la ServiceException que vamos a lanzar
	 * @return ExcepcionesEscenario que nos permite incluir la excepcion que
	 *         esperamos recibir
	 */
	public ExcepcionesEscenario lanzaExceptionControlada(final String errorCode) {
		this.serviceException = new ServiceException(errorCode, "");
		this.controlarExcepcion = true;

		return this;
	}

	/**
	 * La excepcion se guarda en la ejecucion antes de ser lanzada por si fuese
	 * necesario realizar alguna comprobación posterior.
	 * 
	 * @param errorCode
	 *            codigo de error de la ServiceException que vamos a lanzar
	 * @param mensaje
	 *            mensaje de error
	 * @return ExcepcionesEscenario que nos permite incluir la excepcion que
	 *         esperamos recibir
	 */
	public ExcepcionesEscenario lanzaExceptionControlada(
			final String errorCode, final String mensaje) {
		this.serviceException = new ServiceException(errorCode, mensaje);
		this.controlarExcepcion = true;

		return this;
	}

}
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>