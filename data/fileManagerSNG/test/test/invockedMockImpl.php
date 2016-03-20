<?php
	
	$myfile = fopen("test/Midtr2invokeMockImpl.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.test;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.Iterator;


public class Midtr2invokeMockImpl extends Midtr2invoke implements
		Midtr2invokeMock {

	private ArrayList<EscenarioSN> escenariosSN = new ArrayList<EscenarioSN>();
	private ArrayList<EscenarioSN> pasosEjecutadosMidtrMock = new ArrayList<EscenarioSN>();

	private Iterator<EscenarioSN> iteradorEscenarios = null;
	private EscenarioSN pasoActual = null;

	private boolean testVariablesEntrada = false;
	private StringBuilder mensajeWIExceptionVariablesEntrada;

	public StringBuilder getMensajeWIExceptionVariablesEntrada() {
		return mensajeWIExceptionVariablesEntrada;
	}

	public void setTestVariablesEntrada(final boolean testVariablesEntrada) {
		this.testVariablesEntrada = testVariablesEntrada;
	}

	public Midtr2invokeMockImpl setSiguientePasoSinSalida() {
		final EscenarioSN escenario = new EscenarioSN();
		this.escenariosSN.add(escenario);

		return this;
	}

	public Midtr2invokeMockImpl setSiguientePaso(
			final Class<?> claseParaObtenerServicioIT,
			final String nombreMetodoParaObtenerServicioIT) {
		final EscenarioSN escenario = new EscenarioSN();
		escenario.setClaseParaObtenerServicioIT(claseParaObtenerServicioIT);
		escenario
				.setNombreMetodoParaObtenerServicioIT(nombreMetodoParaObtenerServicioIT);
		this.escenariosSN.add(escenario);

		return this;
	}

	public Midtr2invokeMockImpl setSiguientePasoLanzaWIException(
			final String errorCode) {
		final EscenarioSN escenario = new EscenarioSN();
		escenario.setWiException(errorCode);
		this.escenariosSN.add(escenario);

		return this;
	}

	public Midtr2invokeMockImpl setSiguientePasoLanzaWIException(
			final String errorCode, final String mensajeWIException) {
		final EscenarioSN escenario = new EscenarioSN();
		escenario.setWiException(errorCode, mensajeWIException);
		this.escenariosSN.add(escenario);

		return this;
	}

	@Override
	public void process(final com.gfi.webIntegrator.context.IContextoSN in)
			throws java.lang.Exception {

		if (iteradorEscenarios == null || !iteradorEscenarios.hasNext()) {
			throw new SNException(
					"Se esta intentando ejecutar el servicioIT cuando ya no hay mas pasos definidos. Comparar los escenarios con los pasos ejecutados.");
		}

		pasoActual = iteradorEscenarios.next();

		final WIService servicioIT = in.getCurrentItService();

		rellenarCabeceras(servicioIT);

		if (testVariablesEntrada == true) {
			comprobarEntradaAlWIService(servicioIT);
		}

		if (pasoActual.getWiException() != null) {
			pasoActual.getWiException().setService_name(servicioIT.getName());
			pasoActual.getWiException().setService_version(
					servicioIT.getVersion());
			pasoActual.getWiException().setService_module(
					servicioIT.getModule());

			pasosEjecutadosMidtrMock.add(pasoActual);

			throw pasoActual.getWiException();
		}

		try {

			in.setCurrentItService(obtenerServicioIT(servicioIT));

		} catch (final Exception e) {
			throw new SNException(
					obtenerWIException(
							"Se ha producido un error en el metodo process que no ha podido ser tratada.",
							servicioIT, e));
		}
	}

	public void rellenarCabeceras(final WIService servicioIT) {
		final Method[] methods = servicioIT.getClass().getMethods();

		for (final Method method : methods) {
			if (method.getName().startsWith("setcabecera")) {
				try {
					final Type tipoElemento = method.getGenericParameterTypes()[0];

					if (cabeceraSinRellenar(method, servicioIT)) {
						tratarTipoElemento(method, tipoElemento, servicioIT,
								null);
					}
				} catch (final Exception e) {
				}
			}
		}
	}

	private boolean cabeceraSinRellenar(final Method method,
			final WIService servicioIT) {
		boolean cabeceraSinRellenar = true;
		final String nombreGet = new StringBuilder(method.getName()).replace(0,
				3, "get").toString();
		Object cabecera = null;

		try {

			cabecera = servicioIT.getClass().getMethod(nombreGet)
					.invoke(servicioIT, (Object[]) null);

		} catch (final Exception e) {
		}

		if (cabecera != null) {
			cabeceraSinRellenar = false;
		}

		return cabeceraSinRellenar;
	}

	public void comprobarEntradaAlWIService(final WIService wiService) {
		try {
			wiService.returnInParams();
		} catch (final WIException wi) {
			controlarErroresEntradaServicio(wiService);
		} catch (final Exception e) {
			mensajeWIExceptionVariablesEntrada
					.insert(0,
							"La prueba ha finalizado con errores no esperados los campos detectados hasta ese momento son los siguientes (Se incluye el mensaje tras los campos):\n\n");
			mensajeWIExceptionVariablesEntrada.append("\n\n\n Excepcion:\n")
					.append(e.getMessage());
		}

	}

	private void controlarErroresEntradaServicio(final WIService wiService) {
		try {
			wiService.returnInParams();
			mensajeWIExceptionVariablesEntrada
					.append("\n\n")
					.append(wiService.getName())
					.append(": ")
					.append(" Tras incluir estos campos se ha ejecutado correctamente el servicio.\n");

		} catch (final WIException wi) {
			final String trama = new StringBuilder(wiService.getName())
					.append(": ").append(wi.getMessage()).toString();
			if (mensajeWIExceptionVariablesEntrada == null) {
				mensajeWIExceptionVariablesEntrada = new StringBuilder(
						"Hay campos del servicio que no han sido inicializados.")
						.append("\n").append(trama);
			} else {
				if (mensajeWIExceptionVariablesEntrada.toString().contains(
						trama)) {
					return;
				}
				mensajeWIExceptionVariablesEntrada.append("\n").append(trama);
			}
			incluirParametroConError(wi, wiService);
			controlarErroresEntradaServicio(wiService);
		} catch (final Exception e) {
			mensajeWIExceptionVariablesEntrada
					.insert(0,
							"La prueba ha finalizado con errores no esperados los campos detectados hasta ese momento son los siguientes (Se incluye el mensaje tras los campos):\n\n");
			mensajeWIExceptionVariablesEntrada.append("\n\n\n Excepcion:\n")
					.append(e.getMessage());

		}
	}

	private void incluirParametroConError(final WIException wi,
			final WIService wiService) {
		StringBuilder setcampo = new StringBuilder(wi.getMessage());
		setcampo = new StringBuilder(
				setcampo.substring(setcampo.indexOf("\'") + 1));
		setcampo = new StringBuilder(setcampo.substring(0,
				setcampo.indexOf("\'")));
		setcampo.insert(0, "set");

		final Method[] methods = wiService.getClass().getMethods();

		for (final Method method : methods) {
			if (method.getName().equals(setcampo.toString())) {
				try {
					final Type tipoElemento = method.getGenericParameterTypes()[0];
					final String metodoGenerado = tratarTipoElemento(method,
							tipoElemento, wiService, null);
					mensajeWIExceptionVariablesEntrada.append(
							"\n    - Metodo ejecutado para incluir el campo: ")
							.append(metodoGenerado);
				} catch (final Exception e) {
					break;
				}
				break;
			}
		}
	}

	private String tratarTipoElemento(final Method method,
			final Type tipoElemento, final WIService wiService,
			final String padre) {
		final StringBuilder metodoGenerado = new StringBuilder("servicioIT.")
				.append(method.getName()).append("(");
		try {
			if (((Class<?>) tipoElemento).isPrimitive()) {

				if (tipoElemento.toString().equals("int")) {
					metodoGenerado.append("(int)0").append(");");
					method.invoke(wiService, 0);
				} else if (tipoElemento.toString().equals("short")) {
					metodoGenerado.append("(short)0").append(");");
					method.invoke(wiService, (short) 0);
				} else if (tipoElemento.toString().equals("long")) {
					metodoGenerado.append("(long)0").append(");");
					method.invoke(wiService, (long) 0);
				} else if (tipoElemento.toString().equals("float")) {
					metodoGenerado.append("(float)0").append(");");
					method.invoke(wiService, (float) 0);
				} else if (tipoElemento.toString().equals("double")) {
					metodoGenerado.append("(double)0").append(");");
					method.invoke(wiService, (double) 0);
				} else if (tipoElemento.toString().equals("byte")) {
					metodoGenerado.append("(byte)0").append(");");
					method.invoke(wiService, (byte) 0);
				} else if (tipoElemento.toString().equals("boolean")) {
					metodoGenerado.append("false").append(");");
					method.invoke(wiService, false);
				} else if (tipoElemento.toString().equals("char")) {
					metodoGenerado.append("\\\'\\0\\\'").append(");");
					method.invoke(wiService, \'\0\');
				}
			} else {
				final Object object = null;
				if (BindElement.class.isAssignableFrom((Class<?>) tipoElemento)) {
					metodoGenerado.append("new ")
							.append(((Class<?>) tipoElemento).getSimpleName())
							.append("());");
					method.invoke(wiService,
							((Class<?>) tipoElemento).newInstance());
				} else {
					metodoGenerado.append("null").append(");");
					method.invoke(wiService, object);
				}
			}
			return metodoGenerado.toString();
		} catch (final Exception e) {
			metodoGenerado
					.insert(0,
							"El siguiente metodo ha producido una excepcion y no ha podido ser ejecutado.");
			metodoGenerado.append("\n El mensaje del error es el siguiente:")
					.append(e.getMessage());
			return metodoGenerado.toString();
		}
	}

	private WIService obtenerServicioIT(final WIService servicioIT) {
		WIService respuesta = null;

		if (pasoActual.getNombreMetodoParaObtenerServicioIT() != null
				&& pasoActual.getClaseParaObtenerServicioIT() != null) {

			respuesta = ejecutarMetodoEscenarioSNParaObtenerServicioIT(
					pasoActual, servicioIT);
			compararConServicioITEsperado(servicioIT, respuesta);
		} else {
			respuesta = servicioIT;
		}

		pasoActual.setServicioIT(respuesta);
		pasosEjecutadosMidtrMock.add(pasoActual);

		return respuesta;
	}

	private void compararConServicioITEsperado(
			final WIService servicioEsperado, final WIService servicioRespuesta) {
		if (!servicioRespuesta.getClass().equals(servicioEsperado.getClass())) {
			throw new SNException(obtenerMensajeErrorServicioErroneo(
					servicioEsperado, servicioRespuesta));
		}
	}

	private WIService ejecutarMetodoEscenarioSNParaObtenerServicioIT(
			final EscenarioSN pasoActual, final WIService servicioIT) {
		try {
			return (WIService) pasoActual
					.getClaseParaObtenerServicioIT()
					.getMethod(
							pasoActual.getNombreMetodoParaObtenerServicioIT(),
							servicioIT.getClass())
					.invoke(pasoActual.getClaseParaObtenerServicioIT()
							.newInstance(), servicioIT);
		} catch (final IllegalAccessException e) {
			throw new SNException(obtenerWIException(
					"El metodo no es accesible.", servicioIT, e));
		} catch (final IllegalArgumentException e) {
			throw new SNException(obtenerWIException(
					"El metodo no tiene los argumentos esperados.", servicioIT,
					e));
		} catch (final SecurityException e) {
			throw new SNException(obtenerWIException(
					"El metodo tiene una excepción de seguridad.", servicioIT,
					e));
		} catch (final InvocationTargetException e) {
			throw new SNException(obtenerWIException(
					"Error al ejecutar el metodo en la clase Mock.",
					servicioIT, e));
		} catch (final NoSuchMethodException e) {
			throw new SNException(obtenerWIException(
					"El metodo no existe en la clase Mock.", servicioIT, e));
		} catch (final InstantiationException e) {
			throw new SNException(
					obtenerWIException(
							"No se ha podido instanciar la clase para obtener el mock correspondiente.",
							servicioIT, e));
		}

	}

	private WIException obtenerWIException(final String detalle,
			final WIService servicioIT, final Exception e) {

		final StringBuilder sb = new StringBuilder();

		sb.append(detalle)
				.append(" ")
				.append(e.getClass().getSimpleName())
				.append(" ")
				.append("al ejecutar el metodo ejecutarMetodoEscenarioSNParaObtenerServicioIT() para obtener la respuesta del ServicioIT \'")
				.append(servicioIT.getClass().getSimpleName())
				.append("\' y el metodo del mock del servicioIT \'")
				.append(pasoActual.getNombreMetodoParaObtenerServicioIT())
				.append("\'.");

		final WIException wi = new WIException(sb.toString());
		wi.initCause(e);
		wi.setService_name(servicioIT.getName());
		wi.setService_version(servicioIT.getVersion());
		wi.setService_module(servicioIT.getModule());

		return wi;
	}

	private WIException obtenerMensajeErrorServicioErroneo(
			final WIService servicioEsperado, final WIService servicioRespuesta) {

		final StringBuilder sb = new StringBuilder();

		sb.append("El servicioIT de respuesta: ")
				.append(servicioRespuesta.getClass().getSimpleName())
				.append("No se corresponde con el esperado: ")
				.append(servicioEsperado.getClass().getSimpleName())
				.append(". Al ejecutar el mock de Midtr2Invoke con el escenario: ")
				.append(pasoActual.toString());

		final WIException wi = new WIException(sb.toString());
		wi.setService_name(servicioEsperado.getName());
		wi.setService_version(servicioEsperado.getVersion());
		wi.setService_module(servicioEsperado.getModule());

		return wi;
	}

	public ArrayList<EscenarioSN> getEscenariosSN() {
		return escenariosSN;
	}

	public ArrayList<EscenarioSN> getPasosEjecutadosMidtrMock() {
		return pasosEjecutadosMidtrMock;
	}

	public void setEscenariosSN(final ArrayList<EscenarioSN> escenariosSN) {
		this.escenariosSN = escenariosSN;
	}

	protected void iniciarEscenario() {
		iteradorEscenarios = escenariosSN.iterator();
		testVariablesEntrada = false;
		mensajeWIExceptionVariablesEntrada = null;
		pasosEjecutadosMidtrMock = new ArrayList<EscenarioSN>();
	}
}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>