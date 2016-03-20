<?php


	$myfile = fopen("flow/utilidades/AbstractDaoMock.java", "w") or die("Unable to open file!");


	$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.utilidades;

import java.lang.reflect.InvocationTargetException;
import java.util.HashMap;

import lombok.Setter;


public abstract class AbstractDaoMock {
	private final HashMap<String, Escenario> escenarios = new HashMap<String, Escenario>();

	@Setter
	private String escenarioActivo;

	/**
	 * * Metodo que nos permite ejecutar el escenario que hemos incluido ya sea
	 * incluyendo el dto, el nombre del mÃ©todo o la excepcion que vamos a
	 * lanzar.
	 * 
	 * @return OutDto
	 * @throws ServiceException
	 */
	@SuppressWarnings("unchecked")
	public <T> T ejecutarMockDao() throws ServiceException {
		final Escenario escenarioActivo = escenarios.get(this.escenarioActivo);

		if (this.escenarioActivo == null
				|| escenarios.get(this.escenarioActivo) == null) {
			throw new ServiceException(
					"",
					obtenerMensajeErrorCompleto("No se ha podido ejecutar el dao correctamente porque el escenario activo no es vÃ¡lido."));
		}

		Object outDTO = null;

		try {
			if (escenarioActivo.getExcepcionesEscenario().getServiceException() != null) {
				throw escenarioActivo.getExcepcionesEscenario()
						.getServiceException();
			}
		} catch (final ServiceException ServiceException) {
			if (escenarioActivo.getExcepcionesEscenario()
					.isControlarExcepcion() == true) {
				escenarioActivo.getExcepcionesEscenario()
						.setExcepcionControlada(ServiceException);
			}
			throw ServiceException;
		}

		if (escenarioActivo.getOutDTO() != null) {
			outDTO = escenarioActivo.getOutDTO();
		} else {
			outDTO = ejecutarMetodoEscenarioParaObtenerDTO(escenarioActivo);
		}

		return (T) outDTO;
	}

	@SuppressWarnings("unchecked")
	private <T> T ejecutarMetodoEscenarioParaObtenerDTO(
			final Escenario escenarioActivo) {
		Object outDTO = null;

		try {
			if (escenarioActivo.getNombreMetodoParaObtenerOutDTO() != null) {
				outDTO = this
						.getClass()
						.getMethod(
								escenarioActivo
										.getNombreMetodoParaObtenerOutDTO())
						.invoke(this, (Object[]) null);
			} else {
				throw new ServiceException(
						"",
						obtenerMensajeErrorCompleto("Se ha de incluir un dto o el nombre de un método del mock del dao para poder ejecutar un escenario."));
			}
		} catch (final IllegalAccessException e) {
			throw new ServiceException("", obtenerMensajeErrorCompleto(
					"El método no es accesible.", e), e);
		} catch (final IllegalArgumentException e) {
			throw new ServiceException("", obtenerMensajeErrorCompleto(
					"El método no tiene los argumentos esperados.", e), e);
		} catch (final SecurityException e) {
			throw new ServiceException("", obtenerMensajeErrorCompleto(
					"El método tiene una excepción de seguridad.", e), e);
		} catch (final InvocationTargetException e) {
			ServiceException cause = (ServiceException) e.getCause();

			throw new ServiceException(
					cause.getErrorCode(),
					obtenerMensajeErrorCompleto(
							"Error al ejecutar el método en la clase Mock.", e),
					e);
		} catch (final NoSuchMethodException e) {
			throw new ServiceException("", obtenerMensajeErrorCompleto(
					"El método no existe en la clase Mock.", e), e);
		}

		return (T) outDTO;
	}

	private String obtenerMensajeErrorCompleto(final String detalle,
			final Exception e) {

		final StringBuilder sb = new StringBuilder();

		sb.append(detalle)
				.append(" ")
				.append(e.getClass().getSimpleName())
				.append(" ")
				.append("al ejecutar el metodo ejecutarMockDao() Con el escenario \'")
				.append(escenarioActivo)
				.append("\' y el metodo del mock del dao \'")
				.append(escenarios.get(escenarioActivo)
						.getNombreMetodoParaObtenerOutDTO()).append("\'.");

		return sb.toString();
	}

	private String obtenerMensajeErrorCompleto(final String detalle) {

		final StringBuilder sb = new StringBuilder();

		sb.append(detalle)
				.append(" ")
				.append("al ejecutar el metodo ejecutarMockDao() Con el escenario \'")
				.append(escenarioActivo)
				.append("\' y el metodo del mock del dao \'")
				.append(escenarios.get(escenarioActivo)
						.getNombreMetodoParaObtenerOutDTO()).append("\'.");

		return sb.toString();
	}

	/**
	 * Crear un escenario vacio con un identificador. Principalmente para
	 * incluir una excepcion que vamos a tener que tratar.
	 * 
	 * @param identificadorEscenario
	 *            String que identifica el nombre del escenario que vamos a
	 *            utilizar
	 * @return ExcepcionesEscenario objeto sobre el que vamos a incluir la
	 *         excepcion que vamos a lanzar y sobre el que vamos a poder incluir
	 *         la excepcion esperada
	 */
	public ExcepcionesEscenario incluirEscenario(
			final String identificadorEscenario) {
		final Escenario escenario = new Escenario();
		escenario.setIdentificadorEscenario(identificadorEscenario);
		setEscenarioActivo(identificadorEscenario);

		escenarios.put(identificadorEscenario, escenario);
		return escenario.getExcepcionesEscenario();
	}

	/**
	 * Crear un escenario vacio con un identificador. Principalmente para
	 * incluir una excepcion que vamos a tener que tratar.
	 * 
	 * @param identificadorEscenario
	 *            String que identifica el nombre del escenario que vamos a
	 *            utilizar
	 * @param nombreMetodoParaObtenerOutDTO
	 *            String que identifica el nombre del metodo del dao mock que va
	 *            a ejecuctar el escenario
	 * @return ExcepcionesEscenario objeto sobre el que vamos a incluir la
	 *         excepcion que vamos a lanzar y sobre el que vamos a poder incluir
	 *         la excepcion esperada
	 */
	public ExcepcionesEscenario incluirEscenario(
			final String identificadorEscenario,
			final String nombreMetodoParaObtenerOutDTO) {
		final Escenario escenario = new Escenario();
		escenario.setIdentificadorEscenario(identificadorEscenario);
		setEscenarioActivo(identificadorEscenario);

		escenario
				.setNombreMetodoParaObtenerOutDTO(nombreMetodoParaObtenerOutDTO);

		escenarios.put(identificadorEscenario, escenario);
		return escenario.getExcepcionesEscenario();
	}

	/**
	 * Crear un escenario vacio con un identificador. Principalmente para
	 * incluir una excepcion que vamos a tener que tratar.
	 * 
	 * @param identificadorEscenario
	 *            String que identifica el nombre del escenario que vamos a
	 *            utilizar
	 * @param nombreMetodoParaObtenerOutDTO
	 *            Object con el OutDto que va a devolver el metodo del dao mock
	 *            que va a ejecuctar el escenario
	 * @return ExcepcionesEscenario objeto sobre el que vamos a incluir la
	 *         excepcion que vamos a lanzar y sobre el que vamos a poder incluir
	 *         la excepcion esperada
	 */
	public ExcepcionesEscenario incluirEscenario(
			final String identificadorEscenario, final Object outDTO) {
		final Escenario escenario = new Escenario();
		escenario.setIdentificadorEscenario(identificadorEscenario);
		setEscenarioActivo(identificadorEscenario);

		escenario.setOutDTO(outDTO);

		escenarios.put(identificadorEscenario, escenario);
		return escenario.getExcepcionesEscenario();
	}

	/**
	 * Metodo que devuelve la excepcion controlada que se ha lanzado.
	 * 
	 * @param escenarioActivo
	 *            String que identifica el nombre del escenario con el id
	 *            especificado
	 * @return ServiceException excepcion que se ha lanzado en el dao
	 */
	public ServiceException obtenerServiceExceptionControlada(
			final String escenarioActivo) {
		final Escenario escenario = escenarios.get(escenarioActivo);
		if (escenario == null) {
			throw new ServiceException(
					"",
					obtenerMensajeErrorCompleto("Nombre de escenario no encontrado."));
		}

		return escenario.getExcepcionesEscenario().getExcepcionControlada();
	}

	/**
	 * Metodo que devuelve la excepcion controlada que se ha lanzado con el
	 * escenario actualmente activo.
	 * 
	 * @return ServiceException excepcion que se ha lanzado en el dao
	 */
	public ServiceException obtenerServiceExceptionControlada() {
		final Escenario escenario = escenarios.get(escenarioActivo);
		return escenario.getExcepcionesEscenario().getExcepcionControlada();
	}

}

';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>