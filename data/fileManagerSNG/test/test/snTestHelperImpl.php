<?php
	
	$myfile = fopen("test/SNTestHelperImpl.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.test;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Set;

import javax.naming.NamingException;

import org.apache.camel.CamelContext;
import org.apache.camel.CamelException;
import org.apache.camel.ProducerTemplate;
import org.apache.camel.RuntimeCamelException;
import org.apache.camel.impl.DefaultCamelContext;
import org.apache.camel.impl.DefaultProducerTemplate;
import org.apache.camel.util.jndi.JndiContext;
import org.apache.commons.lang.StringUtils;
import org.dom4j.Attribute;
import org.dom4j.Document;
import org.dom4j.DocumentException;
import org.dom4j.Element;
import org.dom4j.Node;
import org.dom4j.io.SAXReader;
import org.reflections.Reflections;


import es.cajamadrid.servicios.BSC.StructCabeceraServicioNegocio;

public class SNTestHelperImpl extends Midtr2invoke implements SNTestHelper {

	private final String PAQUETERIA_SN = "com.bankia.sn";

	private final WIService servicioNegocio;
	private final Midtr2invokeMockImpl midtr2invokeMock;

	private final StructCabeceraServicioNegocio cabeceraSN;
	private final ProducerTemplate templateCamel;
	private final ProducerTemplate templateCamelPruebaIntegrada;

	private boolean orquestationInitIncluido = false;
	HashMap<String, Object> beansFicheroRutas = new HashMap<String, Object>();
	HashMap<String, Class<? extends TransformerBase>> clasesBeansFicheroRutas = new HashMap<String, Class<? extends TransformerBase>>();
	HashMap<String, String> beansIncluidosEnContexto = new HashMap<String, String>();
	ArrayList<String> sentenciasFrom = new ArrayList<String>();
	ArrayList<String> sentenciasDirect = new ArrayList<String>();

	private final StringBuilder mensajeWIExceptionErroresDefinicionContexto;

	public StringBuilder getMensajeWIExceptionErroresDefinicionContexto() {
		return mensajeWIExceptionErroresDefinicionContexto;
	}

	/**
	 * Este constructor instancia el contexto de camel para la ejecucion de las
	 * pruebas. Para ello necesita una instancia del servicio de negocio a
	 * ejecutar y la version del servicio de negocio para poder validar que los
	 * campos con id unico se han renombrado correctamente.
	 * 
	 * @param servicioNegocio
	 *            Servicio de Negocio que se desea probar
	 * @param versionServicioNegocio
	 *            Version que estamos incluyendo del servicio de negocio
	 */
	public SNTestHelperImpl(final WIService servicioNegocio,
			final int versionServicioNegocio) {
		this.servicioNegocio = servicioNegocio;
		midtr2invokeMock = new Midtr2invokeMockImpl();
		cabeceraSN = obtenerValoresDefectoCabeceraSNG();
		incluirEnCabeceraVersionSN(versionServicioNegocio);

		mensajeWIExceptionErroresDefinicionContexto = new StringBuilder();

		cargarClasesTransformadoras();
		tratarFicheroRutas();
		comprobarSentenciasDirect();

		if (mensajeWIExceptionErroresDefinicionContexto.length() > 0) {
			throw new SNException(
					obtenerWIException(mensajeWIExceptionErroresDefinicionContexto
							.append("\n").toString()));
		}

		templateCamel = iniciarContextoCamel();
		templateCamelPruebaIntegrada = iniciarContextoCamelPruebaIntegrada();
	}

	private void incluirEnCabeceraVersionSN(final int versionServicioNegocio) {
		try {
			cabeceraSN.setVersionServicioNegocio(new Integer(
					versionServicioNegocio).toString());
		} catch (final WIException wi) {
			throw new SNException(wi);
		}
	}

	private void comprobarSentenciasDirect() {
		for (final String direct : sentenciasDirect) {
			if (noExisteEnNingunFrom(direct)) {
				mensajeWIExceptionErroresDefinicionContexto
						.insert(0,
								new StringBuilder(
										"\n- Se ha declarado la sentencia \'direct://\'(\'")
										.append(direct)
										.append("\')\n Con un valor que no existe en ninguna etiqueta from.")
										.toString());
			}
		}
	}

	private boolean noExisteEnNingunFrom(final String direct) {
		for (final String from : sentenciasFrom) {
			if (direct.contains(from)) {
				return false;
			}
		}
		return true;
	}

	private void cargarClasesTransformadoras() {
		final Reflections reflections = new Reflections(PAQUETERIA_SN);
		final Set<Class<? extends TransformerBase>> listaClases = reflections
				.getSubTypesOf(TransformerBase.class);
		listaClases.addAll(reflections.getSubTypesOf(TransformerBaseIn.class));
		listaClases.addAll(reflections.getSubTypesOf(TransformerBaseOut.class));

		for (final Class<? extends TransformerBase> clase : listaClases) {
			clasesBeansFicheroRutas.put(clase.getSimpleName(), clase);
		}
	}

	public void ejecutarTest(final boolean validarCamposEntrada) {
		midtr2invokeMock.iniciarEscenario();
		midtr2invokeMock.setTestVariablesEntrada(validarCamposEntrada);

		incluirCabeceraSN(servicioNegocio);

		if (validarCamposEntrada == true) {
			midtr2invokeMock.rellenarCabeceras(servicioNegocio);
			midtr2invokeMock.comprobarEntradaAlWIService(servicioNegocio);
		}

		final String idUnicoServicioNegocio = obtenerIdUnicoServicioNegocio(servicioNegocio);

		enviarCuerpo(templateCamel, idUnicoServicioNegocio);
	}

	public void ejecutarTestIntegrado() {
		incluirCabeceraSN(servicioNegocio);
		final String idUnicoServicioNegocio = obtenerIdUnicoServicioNegocio(servicioNegocio);

		enviarCuerpo(templateCamelPruebaIntegrada, idUnicoServicioNegocio);
	}

	private String obtenerIdUnicoServicioNegocio(final WIService servicioNegocio) {
		try {
			return obtenerCabeceraSN(servicioNegocio)
					.getIdUnicoServicioNegocio();
		} catch (final WIException wi) {
			throw new SNException(wi);
		}
	}

	private void enviarCuerpo(final ProducerTemplate template,
			final String idUnicoServicioNegocio) {

		try {
			template.sendBody(construirStartPoint(), servicioNegocio);

		} catch (Exception e) {

			if ((e instanceof CamelException)
					|| (e instanceof RuntimeCamelException)) {
				if (e.getCause() != null) {
					e = (Exception) e.getCause();
				}
			}

			final StringBuilder mensajeExcepcion = new StringBuilder();

			if (noSeHaBorradoReferenciaCorrectamente(idUnicoServicioNegocio)) {
				mensajeExcepcion
						.append("\n- No se ha eliminado la referencia al servicio de negocio porque Se ha producido una excepcion interna con el siguiente mensaje:\n")
						.append(e.getMessage())
						.append("\n\n  Utilizar el metodo \'eliminarReferenciaServicioNegocio\' para eliminarla al finalizar el flujo.");
			}

			if (midtr2invokeMock != null
					&& midtr2invokeMock.getMensajeWIExceptionVariablesEntrada() != null) {
				mensajeExcepcion
						.append("\n\n- Tambien se han detectado los siguientes errores los datos de entrada:\n  ")
						.append(midtr2invokeMock
								.getMensajeWIExceptionVariablesEntrada()
								.toString());
			}

			throw new SNException(obtenerWIException(
					mensajeExcepcion.toString(), e));
		}

		if (noSeHaBorradoReferenciaCorrectamente(idUnicoServicioNegocio)) {
			throw new SNException(
					obtenerWIException("\n- No se ha eliminado la referencia al servicio de negocio ejecutado del controlador.\n  Utilizar el metodo \'eliminarReferenciaServicioNegocio\' para eliminarla al finalizar el flujo."));
		}

		if (midtr2invokeMock != null
				&& midtr2invokeMock.getMensajeWIExceptionVariablesEntrada() != null) {
			throw new SNException(midtr2invokeMock
					.getMensajeWIExceptionVariablesEntrada().toString());
		}
	}

	private boolean noSeHaBorradoReferenciaCorrectamente(
			final String idUnicoServicioNegocio) {
		boolean noSeHaBorradoReferenciaCorrectamente = false;
		String nombreSuperClase = null;
		Object bean = null;
		Object abstractControlador = null;
		for (final String idBean : beansFicheroRutas.keySet()) {
			bean = beansFicheroRutas.get(idBean);
			nombreSuperClase = bean.getClass().getSuperclass().getSimpleName();
			if (nombreSuperClase.toLowerCase().contains("controlador")) {
				abstractControlador = bean;
				try {
					noSeHaBorradoReferenciaCorrectamente = (Boolean) abstractControlador
							.getClass()
							.getMethod("contieneReferenciaServicioNegocio",
									String.class)
							.invoke(abstractControlador, idUnicoServicioNegocio);
				} catch (final Exception e) {
					throw new SNException(
							obtenerWIException(
									new StringBuilder(
											"\n- La clase controladora \'")
											.append(idBean)
											.append("\' no tiene el metodo \'contieneReferenciaServicioNegocio\' en la clase AbstractControladorSNG de la que extiende.")
											.toString(), e));
				}
			}

		}

		if (noSeHaBorradoReferenciaCorrectamente == true) {
			borrarReferenciaServicioNegocio(abstractControlador,
					idUnicoServicioNegocio);
		}

		return noSeHaBorradoReferenciaCorrectamente;
	}

	private void borrarReferenciaServicioNegocio(
			final Object abstractControlador,
			final String idUnicoServicioNegocio) {
		try {
			abstractControlador
					.getClass()
					.getMethod("eliminarReferenciaServicioNegocio",
							String.class)
					.invoke(abstractControlador, idUnicoServicioNegocio);
		} catch (final Exception e) {
			throw new SNException(
					obtenerWIException(
							new StringBuilder("\n- La clase controladora \'")
									.append(abstractControlador.getClass()
											.getSimpleName())
									.append("\' no tiene el metodo \'eliminarReferenciaServicioNegocio\' en la clase AbstractControladorSNG de la que extiende.")
									.toString(), e));
		}
	}

	public void incluirCabeceraSN(final WIService servicioNegocio) {
		try {
			servicioNegocio
					.getClass()
					.getMethod("setcabeceraNegocio",
							StructCabeceraServicioNegocio.class)
					.invoke(servicioNegocio, cabeceraSN);
			servicioNegocio.getInParams().set("cabeceraNegocio", cabeceraSN);

		} catch (final Exception e) {
			throw new SNException(
					obtenerWIException(
							new StringBuilder(
									"\n- Error al incluir la cabecera de negocio en el servicio: ")
									.append(servicioNegocio.getClass()
											.getCanonicalName())
									.append(" y la cabecera siguiente:\n  ")
									.append(cabeceraSN.toString()).toString(),
							e));
		}
	}

	private StructCabeceraServicioNegocio obtenerCabeceraSN(
			final WIService servicioNegocio) {
		try {
			return (StructCabeceraServicioNegocio) servicioNegocio.getClass()
					.getMethod("getcabeceraNegocio")
					.invoke(servicioNegocio, (Object[]) null);
		} catch (final Exception e) {
			throw new SNException(
					obtenerWIException(
							new StringBuilder(
									"\n- Error al incluir la cabecera de negocio en el servicio: ")
									.append(servicioNegocio.getClass()
											.getCanonicalName())
									.append(" y la cabecera siguiente:\n  ")
									.append(cabeceraSN.toString()).toString(),
							e));
		}
	}

	private StructCabeceraServicioNegocio obtenerValoresDefectoCabeceraSNG() {
		StructCabeceraServicioNegocio cabeceraSN = new StructCabeceraServicioNegocio();

		cabeceraSN = new StructCabeceraServicioNegocio();

		try {

			cabeceraSN.setAplicacionLocal("O2");
			cabeceraSN.setCanalDistribucion("0371"); // Canal tiene que ser el
														// 0371 pq es el de OIE,
														// si esto está mal
														// informado hace que no
														// se escriba en Ambar
														// la trama
			cabeceraSN.setCodigoAgenteTramitador("0001");// Será el usuario que
															// está haciendo la
															// petición.
			cabeceraSN.setCodigoOnlineOffLine("");
			cabeceraSN.setCodigoPrograma("AQ");
			cabeceraSN.setEngancheVisual("OEBEA999");
			cabeceraSN.setEntornoOperativo("PU");
			cabeceraSN.setFechaRealizacionOperacion("2015-08-24");
			cabeceraSN.setHoraRealizacionOperacion("15.32.02");
			cabeceraSN.setHusoHorario("");
			cabeceraSN.setIdentificadorAplicacionFuncional("");
			cabeceraSN.setIdentificadorContrato("2300003765500");
			cabeceraSN.setIdentificadorProducto("");
			cabeceraSN.setIdioma("ES");
			cabeceraSN.setIdUnicoServicioNegocio("1");
			cabeceraSN.setIdUnicoPeticionario("1");
			cabeceraSN.setNombrePeticionario("");
			cabeceraSN.setNombreServicioNegocio(servicioNegocio.getClass()
					.getSimpleName());
			cabeceraSN.setPlataformaOrigen("O2");
			cabeceraSN.setTipoPeticionario("SPR");
			cabeceraSN.setUnidadOrganizadoraAutorizadora("");
			cabeceraSN.setUnidadOrganizativaResponsable("000011780098");
			cabeceraSN.setUsuario("    "); // Este campo tiene que ir informado
											// a 4 blancos siempre
			cabeceraSN.setUsuarioAutorizador("");
			cabeceraSN.setVersionPeticionario("1");
			cabeceraSN.setVersionServicioNegocio(null);

		} catch (final WIException wi) {
			throw new SNException(wi);
		}

		return cabeceraSN;
	}

	/**
	 * Crear un escenarioSN vacio con un identificador. Principalmente para
	 * incluir una excepcion que vamos a tener que tratar.
	 * 
	 * @param identificadorEscenarioSN
	 *            String que identifica el nombre del escenarioSN que vamos a
	 *            utilizar
	 * @return ExcepcionesEscenarioSN objeto sobre el que vamos a incluir la
	 *         excepcion que vamos a lanzar y sobre el que vamos a poder incluir
	 *         la excepcion esperada
	 */
	public Midtr2invokeMock setPrimerPasoSinSalida() {
		final ArrayList<EscenarioSN> escenariosSN = new ArrayList<EscenarioSN>();
		final EscenarioSN escenario = new EscenarioSN();
		escenariosSN.add(escenario);
		midtr2invokeMock.setEscenariosSN(escenariosSN);

		return midtr2invokeMock;
	}

	/**
	 * Crear un escenarioSN vacio con un identificador. Principalmente para
	 * incluir una excepcion que vamos a tener que tratar.
	 * 
	 * @param identificadorEscenarioSN
	 *            String que identifica el nombre del escenarioSN que vamos a
	 *            utilizar
	 * @return ExcepcionesEscenarioSN objeto sobre el que vamos a incluir la
	 *         excepcion que vamos a lanzar y sobre el que vamos a poder incluir
	 *         la excepcion esperada
	 */
	public Midtr2invokeMock setPrimerPaso(
			final Class<?> claseParaObtenerServicioIT,
			final String nombreMetodoParaObtenerServicioIT) {
		final ArrayList<EscenarioSN> escenariosSN = new ArrayList<EscenarioSN>();
		final EscenarioSN escenario = new EscenarioSN();
		escenario.setClaseParaObtenerServicioIT(claseParaObtenerServicioIT);
		escenario
				.setNombreMetodoParaObtenerServicioIT(nombreMetodoParaObtenerServicioIT);
		escenariosSN.add(escenario);
		midtr2invokeMock.setEscenariosSN(escenariosSN);

		return midtr2invokeMock;
	}

	/**
	 * Crear un escenarioSN vacio con un identificador. Principalmente para
	 * incluir una excepcion que vamos a tener que tratar.
	 * 
	 * @param identificadorEscenarioSN
	 *            String que identifica el nombre del escenarioSN que vamos a
	 *            utilizar
	 * @return ExcepcionesEscenarioSN objeto sobre el que vamos a incluir la
	 *         excepcion que vamos a lanzar y sobre el que vamos a poder incluir
	 *         la excepcion esperada
	 */
	public Midtr2invokeMock setPrimerPasoLanzaWIException(final String errorCode) {
		final ArrayList<EscenarioSN> escenariosSN = new ArrayList<EscenarioSN>();
		final EscenarioSN escenario = new EscenarioSN();
		escenario.setWiException(errorCode);
		escenariosSN.add(escenario);
		midtr2invokeMock.setEscenariosSN(escenariosSN);

		return midtr2invokeMock;
	}

	/**
	 * Crear un escenarioSN vacio con un identificador. Principalmente para
	 * incluir una excepcion que vamos a tener que tratar.
	 * 
	 * @param identificadorEscenarioSN
	 *            String que identifica el nombre del escenarioSN que vamos a
	 *            utilizar
	 * @return ExcepcionesEscenarioSN objeto sobre el que vamos a incluir la
	 *         excepcion que vamos a lanzar y sobre el que vamos a poder incluir
	 *         la excepcion esperada
	 */
	public Midtr2invokeMock setPrimerPasoLanzaWIException(
			final String errorCode, final String mensajeWIException) {
		final ArrayList<EscenarioSN> escenariosSN = new ArrayList<EscenarioSN>();
		final EscenarioSN escenario = new EscenarioSN();
		escenario.setWiException(errorCode, mensajeWIException);
		escenariosSN.add(escenario);
		midtr2invokeMock.setEscenariosSN(escenariosSN);

		return midtr2invokeMock;
	}

	public StructCabeceraServicioNegocio obtenerCabeceraServicioNegocio() {
		return cabeceraSN;
	}

	private ProducerTemplate iniciarContextoCamel() {
		return iniciarContextoCamel(false);
	}

	private ProducerTemplate iniciarContextoCamelPruebaIntegrada() {
		return iniciarContextoCamel(true);
	}

	private ProducerTemplate iniciarContextoCamel(final boolean pruebaIntegrada) {
		CamelContext camelContext = null;
		ProducerTemplate template;
		try {
			final JndiContext context = new JndiContext();

			incluirContexto(context, pruebaIntegrada);

			incluirClasesTransformadorasSN(context);

			if (orquestationInitIncluido == false) {
				throw new SNException(
						obtenerWIException("El bean OrquestationInit con method \'init\' es obligatorio en la ruta principal"));
			}

			camelContext = new DefaultCamelContext(context);
			camelContext.addRoutes(new CamelRouteListener());
			template = new DefaultProducerTemplate(camelContext);

			camelContext.start();

			Thread.currentThread();
			Thread.sleep(2000);

			template.start();

		} catch (final Exception e) {
			throw new SNException(e);
		}

		return template;
	}

	private void incluirContexto(final JndiContext context,
			final boolean pruebaIntegrada) throws Exception {
		if (pruebaIntegrada == false) {
			context.bind("Midtr2invoke", midtr2invokeMock);
		} else {
			context.bind("Midtr2invoke", new Midtr2invoke());
		}
		context.bind("OrquestationInit", new OrquestationInit());
		context.bind("RegisterToXML", new ToXML());
	}

	private void incluirClasesTransformadorasSN(final JndiContext context)
			throws NamingException {
		for (final String idBean : beansFicheroRutas.keySet()) {
			context.bind(idBean, beansFicheroRutas.get(idBean));
		}
	}

	private void tratarFicheroRutas() {

		final String rutaFichero = obtenerRutaFicheroRutas();
		final FileInputStream fichero = obtenerFicheroRutas(rutaFichero
				.toString());

		final Document documento = parseXML(fichero);

		incluirClasesTransformadoras(documento);
	}

	private void incluirClasesTransformadoras(final Document documento) {

		@SuppressWarnings("unchecked")
		final List<Node> listaNodosRoute = documento.selectNodes("//routes/*");

		for (final Node nodoRoute : listaNodosRoute) {
			if ("route".equals(nodoRoute.getName())) {
				final String idRoute = nodoRoute.valueOf("@id");

				comprobarSiIdRouteTerminaConVersionSN(idRoute);

				tratarRuta(idRoute, nodoRoute);
			}
		}
	}

	@SuppressWarnings("unchecked")
	private void tratarRuta(final String idRoute, final Node nodoRoute) {
		final List<Node> listaElementosRuta = nodoRoute.selectNodes("*");
		for (final Node nodoElemento : listaElementosRuta) {
			if ("from".equals(nodoElemento.getName())) {
				comprobarSiIdRouteCoincideConFrom(idRoute,
						nodoElemento.valueOf("@uri"));
				sentenciasFrom.add(nodoElemento.valueOf("@uri"));
			} else if ("bean".equals(nodoElemento.getName())) {
				incluirDirect(nodoElemento);
				tratarBean(idRoute, nodoElemento);
			} else if ("to".equals(nodoElemento.getName())) {
				incluirDirect(nodoElemento);
				tratarTo(idRoute, nodoElemento.valueOf("@uri"));
			} else {
				evaluarResto(idRoute, nodoElemento);
			}

		}
	}

	private String obtenerDirect(final Node nodoElemento) {
		final Element elemento = (Element) nodoElemento;
		@SuppressWarnings("unchecked")
		final List<Attribute> attributes = elemento.attributes();

		for (final Attribute attribute : attributes) {
			if (attribute.getValue().contains("direct://")) {
				return attribute.getValue();
			}
		}
		return null;
	}

	private void evaluarResto(final String idRoute, final Node nodoElemento) {
		@SuppressWarnings("unchecked")
		final List<Node> listaElementos = nodoElemento.selectNodes("*");
		for (final Node nodo : listaElementos) {
			incluirDirect(nodo);
			if ("bean".equals(nodo.getName())) {
				tratarBean(idRoute, nodo);
			}
			evaluarResto(idRoute, nodo);
		}
	}

	private void incluirDirect(final Node nodo) {
		String direct = null;

		if (nodo.asXML().contains("direct://")) {
			direct = obtenerDirect(nodo);

			if (direct != null && !direct.equals(obtenerMockResulEsperado())) {
				sentenciasDirect.add(direct);
			}
		}
	}

	private void tratarBean(final String idRoute, final Node nodoElemento) {
		final String campoBeanType = nodoElemento.valueOf("@beanType");
		final String campoRef = nodoElemento.valueOf("@ref");
		final String campoMethod = nodoElemento.valueOf("@method");
		final String campoId = nodoElemento.valueOf("@id");

		if (!"Midtr2invoke".equals(campoBeanType)
				&& !"OrquestationInit".equals(campoBeanType)) {
			comprobarSiIdBeanContieneVersionSN(idRoute, campoId);
			comprobarSiBeanRefContieneVersionSN(idRoute, campoRef);
			comprobarSiBeanTypeContieneVersionSN(idRoute, campoBeanType);
			comprobarRequisitosBean(idRoute, campoRef, campoBeanType);
			incluirBeanEnContexto(idRoute, campoBeanType);
			comprobarSiIdYaUtilizado(idRoute, campoId);
			beansIncluidosEnContexto.put(campoId, campoBeanType);

		} else if ("OrquestationInit".equals(campoBeanType)) {
			comprobarMethodEsInit(idRoute, campoMethod, campoBeanType);
			comprobarRequisitosBeanOrquestationInit(idRoute, campoRef,
					campoBeanType);
			orquestationInitIncluido = true;
		}

	}

	private void comprobarSiIdYaUtilizado(final String idRoute,
			final String campoId) {
		if (beansIncluidosEnContexto.containsKey(campoId)) {
			mensajeWIExceptionErroresDefinicionContexto
					.append(new StringBuilder("\n\n- La ruta: \'")
							.append(idRoute)
							.append("\'\n  Tiene el id: \'")
							.append(campoId)
							.append("\'\n  Pero que ya ha sido incluido anteriormente en otro bean y no pueden repetirse.")
							.toString());
		}
	}

	private void comprobarRequisitosBeanOrquestationInit(final String idRoute,
			final String campoRef, final String campoBeanType) {
		if (beansIncluidosEnContexto.size() > 0) {
			mensajeWIExceptionErroresDefinicionContexto
					.append(new StringBuilder("\n\n- En la ruta: \'")
							.append(idRoute)
							.append("\'\n  El campo OrquestationInit debe ser el primer bean que se declara.")
							.toString());
		}

		if (!campoBeanType.equals(campoRef)) {
			mensajeWIExceptionErroresDefinicionContexto
					.append(new StringBuilder("\n\n- En la ruta: \'")
							.append(idRoute)
							.append("\'\n  Los atributos \'ref\': ")
							.append(campoRef).append("\n  y \'beanType\': \'")
							.append(campoBeanType)
							.append("\'\n  han de coincidir.\'").toString());
		}
	}

	private void comprobarMethodEsInit(final String idRoute,
			final String campoMethod, final String campoBeanType) {
		if (!"init".equals(campoMethod)) {
			mensajeWIExceptionErroresDefinicionContexto
					.append(new StringBuilder("\n\n- En la ruta: \'")
							.append(idRoute)
							.append("\'\n  el atributo method del bean: \'")
							.append(campoBeanType)
							.append("\'\n  ha de ser: \'init\'").toString());
		}
	}

	private void incluirBeanEnContexto(final String idRoute,
			final String campoBeanType) {
		try {
			if (clasesBeansFicheroRutas.containsKey(campoBeanType)) {
				final Class<?> clazz = clasesBeansFicheroRutas
						.get(campoBeanType);
				beansFicheroRutas.put(campoBeanType, clazz.newInstance());
			} else {
				mensajeWIExceptionErroresDefinicionContexto
						.append(new StringBuilder("\n\n- La ruta: \'")
								.append(idRoute)
								.append("\'\n  Tiene el bean: \'")
								.append(campoBeanType)
								.append("\'\n  que no ha podido ser instanciado. Porque el bean no existe en src/main/java.")
								.append("\n  Recordar que la version es un requisito a la hora de nombrar a las clases transformadoras.")
								.toString());
			}
		} catch (final Exception e) {
			mensajeWIExceptionErroresDefinicionContexto
					.append(new StringBuilder("\n\n- La ruta: \'")
							.append(idRoute)
							.append("\'\n  Tiene el bean: \'")
							.append(campoBeanType)
							.append("\'\n  Que no ha podido ser instanciado. La paqueteria esperada es: \'")
							.append(clasesBeansFicheroRutas.get(campoBeanType))
							.append("\'\n  Si se quiere utilizar una diferente se ha de especificar con el metodo inicializarTest que incluye este campo.")
							.append(" El mensaje de la excepcion tratada es el siguiente siguiente:")
							.append(e.getMessage()).toString());
		}
	}

	private void comprobarSiIdRouteCoincideConFrom(final String idRoute,
			final String fromUri) {
		final String uriEsperado = new StringBuilder().append("direct://")
				.append(idRoute).toString();
		if (fromUri == null || !uriEsperado.equals(fromUri)) {
			mensajeWIExceptionErroresDefinicionContexto
					.append(new StringBuilder("\n\n- La ruta: \'")
							.append(idRoute).append("\'\n  Tiene el from: \'")
							.append(fromUri)
							.append("\'\n  cuando deberia ser: \'")
							.append(uriEsperado).append("\'").toString());
		}
	}

	private void tratarTo(final String idRoute, final String uri) {
		if (uri == null) {
			throw new SNException(obtenerWIException(new StringBuilder(
					"\n\n- La ruta: \'").append(idRoute)
					.append("\' Tiene el to sin un uri.\'").toString()));
		}
		final String mockResul = obtenerMockResulEsperado();

		if (!uri.startsWith("direct://") && !mockResul.equals(uri)) {
			formatoUriIncorrecto(idRoute, uri, mockResul);
		}

	}

	private String obtenerMockResulEsperado() {
		return new StringBuilder().append("mock:NULL?RetainFirst=1").toString();
	}

	private void formatoUriIncorrecto(final String idRoute, final String uri,
			final String mockResul) {
		final String toComienzaConDirect = "direct://";

		mensajeWIExceptionErroresDefinicionContexto.append(new StringBuilder(
				"\n\n - La ruta: \'").append(idRoute)
				.append("\'\n  Tiene el to: \'").append(uri)
				.append("\'\n  cuando deberia comenzar por: \'")
				.append(toComienzaConDirect).append("\'\n  o ser:\'")
				.append(mockResul).append("\'").toString());
	}

	private void comprobarRequisitosBean(final String idRoute,
			final String campoRef, final String campoBeanType) {
		if (campoRef == null || campoBeanType == null
				|| !campoBeanType.equals(campoRef)) {
			mensajeWIExceptionErroresDefinicionContexto
					.append(new StringBuilder("\n\n- En la ruta: \'")
							.append(idRoute)
							.append("\'\n  Los atributos \'ref\': \'")
							.append(campoRef).append("\'\n  y \'beanType\': \'")
							.append(campoBeanType)
							.append("\'\n  han de coincidir.").toString());
		}

	}

	private void comprobarSiBeanRefContieneVersionSN(final String idRoute,
			final String campoRef) {
		if (campoContieneNombreServicioNegocio(campoRef)) {
			if (campoRef == null
					|| !campoRef
							.startsWith(obtenerNombreServicioNegocioConVersion())) {
				mensajeWIExceptionErroresDefinicionContexto
						.append(new StringBuilder("\n\n- En la ruta: \'")
								.append(idRoute)
								.append("\' el atributo ref del bean: \'")
								.append(campoRef)
								.append("\'\n  No incluye la version del servicio de negocio.")
								.append("\n  Deberia tener el siguiente formato: \'")
								.append(obtenerNombreServicioNegocioConVersion())
								.append(obtenerSufijoTransformadorServicioNegocio(campoRef))
								.append("\'").toString());
			}
		} else {
			if (campoRef == null
					|| !campoRef
							.endsWith(obtenerSufijoVersionServicioNegocio())) {
				mensajeWIExceptionErroresDefinicionContexto
						.append(new StringBuilder("\n\n- En la ruta: \'")
								.append(idRoute)
								.append("\'\n  El atributo ref del bean: \'")
								.append(campoRef)
								.append("\'\n  No incluye la version del servicio de negocio.")
								.append("\n  Deberia tener el siguiente formato: \'")
								.append(quitarVersionActual(campoRef))
								.append(obtenerSufijoVersionServicioNegocio())
								.append("\'").toString());
			}
		}
	}

	private void comprobarSiBeanTypeContieneVersionSN(final String idRoute,
			final String campoBeanType) {
		if (campoContieneNombreServicioNegocio(campoBeanType)) {
			if (campoBeanType == null
					|| !campoBeanType
							.startsWith(obtenerNombreServicioNegocioConVersion())) {
				mensajeWIExceptionErroresDefinicionContexto
						.append(new StringBuilder("\n\n- En la ruta: \'")
								.append(idRoute)
								.append("\'\n  El atributo BeanType del bean: \'")
								.append(campoBeanType)
								.append("\'\n  no incluye la version del servicio de negocio.")
								.append("\n  Deberia tener el siguiente formato: \'")
								.append(obtenerNombreServicioNegocioConVersion())
								.append(obtenerSufijoTransformadorServicioNegocio(campoBeanType))
								.append("\'").toString());
			}
		} else {
			if (campoBeanType == null
					|| !campoBeanType
							.endsWith(obtenerSufijoVersionServicioNegocio())) {
				mensajeWIExceptionErroresDefinicionContexto
						.append(new StringBuilder("\n\n- En la ruta: \'")
								.append(idRoute)
								.append("\'\n  El atributo BeanType del bean: \'")
								.append(campoBeanType)
								.append("\'\n  no incluye la version del servicio de negocio.")
								.append("\n  Deberia tener el siguiente formato: \'")
								.append(quitarVersionActual(campoBeanType))
								.append(obtenerSufijoVersionServicioNegocio())
								.append("\'").toString());
			}
		}
	}

	private void comprobarSiIdBeanContieneVersionSN(final String idRoute,
			final String campoId) {

		if (campoContieneNombreServicioNegocio(campoId)) {
			if (campoId == null
					|| !campoId
							.startsWith(obtenerNombreServicioNegocioConVersion())) {
				mensajeWIExceptionErroresDefinicionContexto
						.append(new StringBuilder("\n\n- En la ruta: \'")
								.append(idRoute)
								.append("\'\n  El atributo id del bean: \'")
								.append(campoId)
								.append("\'\n  no incluye la version del servicio de negocio.")
								.append("\n  Deberia tener el siguiente formato: \'")
								.append(obtenerNombreServicioNegocioConVersion())
								.append(obtenerSufijoTransformadorServicioNegocio(campoId))
								.append("\'").toString());
			}
		} else {
			if (campoId == null
					|| !campoId.endsWith(obtenerSufijoVersionServicioNegocio())) {
				mensajeWIExceptionErroresDefinicionContexto
						.append(new StringBuilder("\n\n- En la ruta: \'")
								.append(idRoute)
								.append("\'\n  El atributo id del bean: \'")
								.append(campoId)
								.append("\'\n  no incluye la version del servicio de negocio.")
								.append("\n  Deberia tener el siguiente formato: \'")
								.append(quitarVersionActual(campoId))
								.append(obtenerSufijoVersionServicioNegocio())
								.append("\'").toString());
			}
		}

	}

	private void comprobarSiIdRouteTerminaConVersionSN(final String idRoute) {
		if (idRoute == null
				|| !idRoute.endsWith(obtenerSufijoVersionServicioNegocio())) {
			mensajeWIExceptionErroresDefinicionContexto
					.append(new StringBuilder("\n\n- La ruta: \'")
							.append(idRoute)
							.append("\'\n  no incluye la version del servicio de negocio.")
							.append("\n  Deberia tener el siguiente formato: \'")
							.append(quitarVersionActual(idRoute))
							.append(obtenerSufijoVersionServicioNegocio())
							.append("\'.").toString());
		}
	}

	private boolean campoContieneNombreServicioNegocio(final String campo) {
		return campo.toUpperCase().contains(
				servicioNegocio.getClass().getSimpleName().toUpperCase());
	}

	private String obtenerRutaFicheroRutas() {
		final StringBuilder rutaFichero = new StringBuilder();
		rutaFichero.append("src/routes/route-")
				.append(servicioNegocio.getClass().getSimpleName())
				.append(".xml");
		return rutaFichero.toString();
	}

	private Document parseXML(final FileInputStream fichero) {
		try {
			final SAXReader reader = new SAXReader();
			final Document document = reader.read(fichero);

			return document;
		} catch (final DocumentException e) {

			throw new SNException(obtenerWIException(
					"El documento xml no se ha podido parsear.", e));
		}

	}

	private FileInputStream obtenerFicheroRutas(final String rutaFichero) {
		try {
			final FileInputStream fichero = new FileInputStream(rutaFichero);
			return fichero;
		} catch (final FileNotFoundException e) {
			final StringBuilder ficheroNoEncontrado = new StringBuilder();
			ficheroNoEncontrado
					.append("- El fichero de rutas no se ha encontrado en la siguiente ruta o no tiene el formato correcto:\n  ")
					.append(rutaFichero);
			throw new SNException(obtenerWIException(
					ficheroNoEncontrado.toString(), e));
		}
	}

	private String construirStartPoint() {
		final StringBuilder sb = new StringBuilder();
		sb.append("direct://").append("start")
				.append(servicioNegocio.getClass().getSimpleName())
				.append(obtenerSufijoVersionServicioNegocio());

		return sb.toString();
	}

	private String obtenerNombreServicioNegocioConVersion() {
		try {
			return new StringBuilder(servicioNegocio.getClass().getSimpleName())
					.append("_").append(cabeceraSN.getVersionServicioNegocio())
					.toString();
		} catch (final WIException e) {
			throw new SNException(
					"Error al obtener la version del servicio de negocio de la cabecera de negocio");
		}
	}

	private String obtenerSufijoVersionServicioNegocio() {
		try {
			return new StringBuilder("_").append(
					cabeceraSN.getVersionServicioNegocio()).toString();
		} catch (final WIException wi) {
			throw new SNException(
					obtenerWIException(
							"Error al obtener la version del servicio de negocio de la cabecera de negocio",
							wi));
		}
	}

	private Object obtenerSufijoTransformadorServicioNegocio(final String campo) {
		String resultado = campo;
		int posicion = campo.indexOf(new StringBuilder(servicioNegocio
				.getClass().getSimpleName()).append(\'_\').toString());

		if (posicion == -1) {
			resultado = resultado.replace(servicioNegocio.getClass()
					.getSimpleName(), obtenerNombreServicioNegocioConVersion());
		} else {
			posicion = campo.indexOf(\'_\');
			resultado = campo.substring(posicion);

			if (resultado.length() > 2
					&& StringUtils.isNumeric(resultado.substring(1, 2))) {
				resultado = resultado.substring(resultado.indexOf(\'_\', 2));
			}
		}

		return resultado;
	}

	public ArrayList<EscenarioSN> obtenerPasos() {
		return midtr2invokeMock.getEscenariosSN();
	}

	public ArrayList<EscenarioSN> obtenerPasosEjecutados() {
		return midtr2invokeMock.getPasosEjecutadosMidtrMock();
	}

	public boolean seHanEjecutadoTodosLosPasos() {
		return midtr2invokeMock.getEscenariosSN().size() == midtr2invokeMock
				.getPasosEjecutadosMidtrMock().size();
	}

	private String quitarVersionActual(final String campo) {
		String resultado = campo;
		final int posBarraBajaVersion = campo.lastIndexOf(\'_\');

		if (posBarraBajaVersion > 0
				&& StringUtils.isNumeric(campo
						.substring(posBarraBajaVersion + 1))) {
			resultado = campo.substring(0, posBarraBajaVersion);
		}
		return resultado;
	}

	private WIException obtenerWIException(final String mensaje,
			final Exception e) {
		WIException wi = null;

		if ((e instanceof SNException) && (e.getCause() instanceof WIException)) {
			wi = (WIException) e.getCause();
			final StringBuilder sb = new StringBuilder(
					"\nMensaje WIException: \n").append(wi.getMessage())
					.append("\n").append(mensaje);
			wi = new WIException(sb.toString(), wi.getErrorCode(),
					wi.getErrorType());
		} else if (e instanceof WIException) {
			wi = (WIException) e;
			final StringBuilder sb = new StringBuilder(
					"\nMensaje WIException: \n").append(wi.getMessage())
					.append("\n").append(mensaje);
			wi = new WIException(sb.toString(), wi.getErrorCode(),
					wi.getErrorType());
		} else {
			wi = new WIException(mensaje);
			wi.initCause(e);
		}

		wi.setService_name(servicioNegocio.getName());
		wi.setService_version(servicioNegocio.getVersion());
		wi.setService_module(servicioNegocio.getModule());

		return wi;
	}

	private WIException obtenerWIException(final String mensaje) {
		final WIException wi = new WIException(mensaje);

		wi.setService_name(servicioNegocio.getName());
		wi.setService_version(servicioNegocio.getVersion());
		wi.setService_module(servicioNegocio.getModule());

		return wi;
	}
}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>