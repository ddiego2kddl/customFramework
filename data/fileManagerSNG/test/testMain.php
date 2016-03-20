<?php
	
	$myfile = fopen($sng->name."_1_Test.java", "w") or die("Unable to open file!");

	$txt = 'package '.$sng->paqueteria.';

import static org.junit.Assert.assertTrue;

import javax.annotation.Resource;

import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Ignore;
import org.junit.Rule;
import org.junit.Test;
import org.mockito.Mockito;

import '.$sng->paqueteria.'.'.$sng->name.'_1_Controlador;
import '.$sng->paqueteria.'.exception.SNExpectedException;
import '.$sng->paqueteria.'.resources.'.$auxDependenciaName[0].'_Mock;
import '.$sng->paqueteria.'.resources.WiServiceClassMock;
import '.$sng->paqueteria.'.test.SNTestHelper;
import '.$sng->paqueteria.'.test.SNTestHelperImpl;
import '.$sng->paqueteria.'.utilidades.ConstantesSNG_1;

import es.cajamadrid.servicios.SN.'.$sng->name.'.'.$sng->name.';

public class '.$sng->name.'_1_Test {

	private static SNTestHelper testHelper;
	private static '.$sng->name.' servicioNegocio;
	private static '.$sng->name.'_1_Controlador controlador;

	@Resource
	private static ContextSN contexto;

	//private static final String obligatorio_referenciaExp = "El campo referenciaExpediente es obligatorio";

	private static final String METHOD_SIN_RELLAMADA = "sin_Rellamada_OK";
	private static final String METHOD_CON_RELLAMADA = "con_Rellamada_OK";

	@Rule
	public SNExpectedException excepcionEsperada = SNExpectedException.none();

	@BeforeClass
	public static void iniciarContexto() throws Exception {
		controlador = new '.$sng->name.'_1_Controlador();
		servicioNegocio = new '.$sng->name.'();
		testHelper = new SNTestHelperImpl(servicioNegocio, 1);
		contexto = Mockito.mock(ContextSN.class);
	}

	@Before
	public void iniciarDatosEntradaSN() throws WIException {
		darValoresCorrectosEntradaSNG();
	}

	public void darValoresCorrectosEntradaSNG() throws WIException {
		//SETEAR DATOS DE ENTRADA, PEDIR DATOS A MADRID PARA PASAR PRUEBA DE INTEGRACION O BUSCAR EN OI1
		//servicioNegocio.setreferenciaExpediente("24214");
	}

	//TEST CAMPO NULO
	/*
	@Test
	public void referenciaExp_nulo() throws Exception {
		excepcionEsperada.tieneCodigoErrorYMensajeContiene(
				ConstantesSNG_1.COD_ERROR_GENERICO, obligatorio_referenciaExp);

		servicioNegocio.setreferenciaExpediente(null);
		testHelper.ejecutarTest(true);
	}
	*/

	@Test
	public void ejecucionSinRellamada() throws Exception {

		testHelper.setPrimerPaso('.$auxDependenciaName[0].'_Mock.class, METHOD_SIN_RELLAMADA);
		testHelper.ejecutarTest(true);

		assertTrue(testHelper.seHanEjecutadoTodosLosPasos());
	}

	@Test
	public void ejecucionConRellamada() throws Exception {

		testHelper.setPrimerPaso('.$auxDependenciaName[0].'_Mock.class, METHOD_CON_RELLAMADA);
		testHelper.ejecutarTest(true);

		assertTrue(testHelper.seHanEjecutadoTodosLosPasos());
	}

	@Ignore
	@Test
	public void ejecutarPruebaIntegrada() {
		testHelper.ejecutarTestIntegrado();
	}

	@Test(expected = SNException.class)
	public void testTransformarIContextoSN_nulo() {
		IContextoSN contexto = null;
		controlador.transformar(contexto);
	}

	@Test(expected = SNException.class)
	public void testTransformarIContextoSN_SN_nulo() {
		Mockito.when(contexto.getServicioNegocio()).thenReturn(null);
		controlador.transformar(contexto);
	}

	@Test(expected = SNException.class)
	public void testTransformarIContextoSN_SN_cast_error() throws WIException {
		Mockito.when(contexto.getServicioNegocio()).thenReturn(
				new WiServiceClassMock());
		controlador.transformar(contexto);
	}
	
	@Test(expected = SNException.class)
	public void testWIException() {
		controlador = new '.$sng->name.'_1_Controlador();
		WIException e = new WIException("test");
		controlador.errorInvocacionSNG(e, "1");
	}
	
	@Test(expected = SNException.class)
	public void testSNException() {
		controlador = new '.$sng->name.'_1_Controlador();
		SNException e = new SNException("test");
		controlador.errorInvocacionSNG(e, "1");
	}
	
	@Test(expected = SNException.class)
	public void testException() {
		controlador = new '.$sng->name.'_1_Controlador();
		Exception e = new Exception("test");
		controlador.errorInvocacionSNG(e, "1");
	}
	
	@Test(expected = SNException.class)
	public void testExceptionCODE() {
		controlador = new '.$sng->name.'_1_Controlador();
		WIException e = new WIException("test");
		e.setErrorCode(ConstantesSNG_1.COD_ERROR_PTE);
		controlador.errorInvocacionSNG(e, "1");
	}

}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>