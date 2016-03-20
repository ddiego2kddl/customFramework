<?php
	echo "hola";

	$myfile = fopen("flow/utilidades/Utilidades.java", "w") or die("Unable to open file!");


	$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.utilidades;

import java.lang.reflect.Field;
import java.lang.reflect.Method;
import java.lang.reflect.Modifier;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.TreeMap;

import com.gfi.webIntegrator.WIException;

public class Utilidades {

	private static final String EMPTY = "";

	public static void copiarCamposCoincidentes(final Object fuente, final Object destino) {

		try {
			final Map<String, Field> camposFuente = analyze(fuente);
			final Map<String, Field> camposDestino = analyze(destino);
			camposFuente.keySet().retainAll(camposDestino.keySet());
			for (final Entry<String, Field> campoFuenteEntry : camposFuente.entrySet()) {

				final String name = campoFuenteEntry.getKey();
				final Field campoFuente = campoFuenteEntry.getValue();
				final Field campoDestino = camposDestino.get(name);
				campoFuente.setAccessible(true);
				final Object valorCampoFuente = campoFuente.get(fuente);
				if (campoDestino.getType().isAssignableFrom(campoFuente.getType())) {

					if (Modifier.isFinal(campoDestino.getModifiers())) {

						continue;
					}
					campoDestino.setAccessible(true);
					try {

						campoDestino.set(destino, valorCampoFuente);
					}
					catch (final IllegalAccessException e) {

						throw new IllegalStateException("No se puede acceder al campo: " + campoDestino.getName());
					}
				}
				else {

					final Map<String, Field> camposCampoFuente = analyze(valorCampoFuente);
					campoDestino.setAccessible(true);
					Object valorCampoDestino = campoDestino.get(destino);
					if (valorCampoDestino == null) {

						valorCampoDestino = campoDestino.getType().newInstance();
					}

					final Map<String, Field> camposCampoDestino = analyze(valorCampoDestino);
					camposCampoFuente.keySet().retainAll(camposCampoDestino.keySet());
					for (final Entry<String, Field> campoCampoFuenteEntry : camposCampoFuente.entrySet()) {

						final String nombreCampoHijo = campoCampoFuenteEntry.getKey();
						final Field campoHijoFuente = campoCampoFuenteEntry.getValue();
						final Field campoHijoDestino = camposCampoDestino.get(nombreCampoHijo);
						if (campoHijoDestino.getType().isAssignableFrom(campoHijoFuente.getType())) {

							campoHijoFuente.setAccessible(true);
							if (Modifier.isFinal(campoHijoDestino.getModifiers())) {

								continue;
							}
							campoHijoDestino.setAccessible(true);
							try {

								campoHijoDestino.set(valorCampoDestino, campoHijoFuente.get(valorCampoFuente));
							}
							catch (final IllegalAccessException e) {

								throw new IllegalStateException("No se puede acceder al campo: " + campoDestino.getName());
							}
						}
					}
					try {

						campoDestino.set(destino, valorCampoDestino);
					}
					catch (final IllegalAccessException e) {

						throw new IllegalStateException("No se puede acceder al campo: " + campoDestino.getName());
					}

				}
			}
		}
		catch (final Exception e) {

			throw new GenericFlowException(Constantes.COD_ERROR_GENERICO, "Error de reflexión");
		}
	}

	private static Map<String, Field> analyze(final Object objeto) {

		if (objeto == null) {

			throw new NullPointerException();
		}
		final Map<String, Field> mapa = new TreeMap<String, Field>();
		final Class<?> clase = objeto.getClass();
		for (final Field field : clase.getDeclaredFields()) {

			if (!Modifier.isStatic(field.getModifiers())) {

				if (!mapa.containsKey(field.getName())) {

					mapa.put(field.getName(), field);
				}
			}
		}
		return mapa;
	}

	public static List<String> obtenerListaCamposNulos(final Object objeto) {

		try {

			final List<String> listaCamposNulos = new ArrayList<String>();
			if (objeto == null) {

				throw new IllegalArgumentException("El objeto es nulo");
			}
			else {

				final Method[] metodos = objeto.getClass().getDeclaredMethods();
				for (final Method metodo : metodos) {

					final String nombreMetodo = metodo.getName();
					if (nombreMetodo.startsWith("get") && metodo.invoke(objeto) == null) {

						listaCamposNulos.add(primerCaracterCambiado(nombreMetodo.substring(3, nombreMetodo.length())));
					}
				}
			}
			return listaCamposNulos;
		}
		catch (final Exception e) {

			throw new ServiceException(Constantes.COD_ERROR_GENERICO, new StringBuilder(
					"Error al comprobar los campos del objeto: ").append(objeto).toString(), e);
		}

	}

	public static String obtenerStringConcatenada(final Collection<?> collection, final String separador) {

		if (collection == null) {
			return null;
		}
		return obtenerStringConcatenada(collection.iterator(), separador);
	}

	public static String obtenerStringConcatenada(final Iterator<?> it, String separador) {

		// handle null, zero and one elements before building a buffer
		if (it == null) {
			return null;
		}
		if (!it.hasNext()) {
			return EMPTY;
		}
		final Object first = it.next();
		if (!it.hasNext()) {
			return toStringsSeguro("\'" + first + "\'");
		}

		// two or more elements
		final StringBuffer buf = new StringBuffer(256); // Java default is 16, probably too small
		if (first != null) {
			buf.append("\'" + first + "\'");
		}

		while (it.hasNext()) {
			final Object obj = it.next();
			if (separador != null) {
				if (!it.hasNext()) {

					separador = " y ";
				}
				buf.append(separador);
			}
			if (obj != null) {
				buf.append("\'" + obj + "\'");
			}
		}
		return buf.toString();
	}

	public static String toStringsSeguro(final Object obj) {

		return obj == null ? "" : obj.toString();
	}

	public static String primerCaracterCambiado(final String cadena) {

		if (Character.isUpperCase(cadena.substring(0, 1).charAt(0))) {

			return cadena.substring(0, 1).toLowerCase() + cadena.substring(1);
		}
		else {

			return cadena.substring(0, 1).toUpperCase() + cadena.substring(1);
		}
	}
	
	public static Fecha getFecha(es.cajamadrid.servicios.ARQ.Fecha fechaEntrada) throws WIException{
		Fecha fechaSalida = new Fecha();
		fechaSalida.setValor(fechaEntrada.getValor());
		return fechaSalida;
	}
	
	public static ImporteMonetario getImporte(es.cajamadrid.servicios.ARQ.ImporteMonetario importeEntrada) throws WIException {
		ImporteMonetario importeSalida = new ImporteMonetario();
		importeSalida.setImporteConSigno(importeEntrada.getImporteConSigno());
		importeSalida.setNumeroDecimalesImporte(importeEntrada.getNumeroDecimalesImporte());
		es.cajamadrid.servicios.ARQ.Moneda monedaEntrada = importeEntrada.getMonedaBISA();
		Moneda monedaSalida = new Moneda(monedaEntrada.getDivisa(), monedaEntrada.getDigitoControlDivisa());
		importeSalida.setMoneda(monedaSalida);
		return importeSalida;
	}
	
}
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>