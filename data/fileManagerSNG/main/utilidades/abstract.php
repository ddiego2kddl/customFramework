<?php

	$myfile = fopen("utilidades/AbstractControladorSNG_1.java", "w") or die("Unable to open file!");

	$txt = 'package '.$sng->paqueteria.'.utilidades;

import java.util.HashMap;
import java.util.concurrent.ConcurrentHashMap;

import org.apache.camel.CamelException;

import com.gfi.webIntegrator.WIException;

/**
 * Clase de Utilidades para la Reutilización del código de los controladores de
 * los servicios de negocio. Tenemos pendiente sacar las clases de utilidades a
 * un proyecto independiente para que se puedan utlilizar desde cualquier
 * proyecto.
 **/
public abstract class AbstractControladorSNG_1 extends TransformerBase {

	private ConcurrentHashMap<String, HashMap<String, Object>> atributosSNG = new ConcurrentHashMap<String, HashMap<String, Object>>();

	/**
	 * Método que nos permite comprobar si tenemos almacenado en el HashMap del
	 * contexto el atributo recibido por parámetro y en caso de que exista para
	 * este idUnico de servicio de negocio, devolver su valor.
	 * 
	 * @param idUnicoServicioNegocio
	 *            String
	 * @param nombreAtributo
	 *            String
	 * @return Object
	 **/
	public Object obtenerAtributoContexto(final String idUnicoServicioNegocio,
			final String nombreAtributo) throws SNException {
		Object propiedad = null;
		try {
			if (atributosSNG != null
					&& atributosSNG.containsKey(idUnicoServicioNegocio)) {
				final HashMap<String, Object> map = atributosSNG
						.get(idUnicoServicioNegocio);

				if (map.containsKey(nombreAtributo)) {
					propiedad = map.get(nombreAtributo);
				}
			}
		} catch (final Exception e) {
			throw new SNException(e);
		}
		return propiedad;
	}

	/**
	 * Método que nos permite incluir un nuevo atributo en la HashMap del
	 * contexto, la cual nos permite almacenar las diferentes propiedades que se
	 * van necesitando durante la orquestación del servicio.
	 * 
	 * @param idUnicoServicioNegocio
	 *            String
	 * @param nombreAtributo
	 *            String
	 * @param atributo
	 *            Object
	 **/
	public void incluirAtributoContexto(final String idUnicoServicioNegocio,
			final String nombreAtributo, final Object atributo) {
		if (atributosSNG == null) {
			atributosSNG = new ConcurrentHashMap<String, HashMap<String, Object>>();
		}

		if (!atributosSNG.containsKey(idUnicoServicioNegocio)) {
			atributosSNG.put(idUnicoServicioNegocio,
					new HashMap<String, Object>());
		}

		final HashMap<String, Object> map = atributosSNG
				.get(idUnicoServicioNegocio);
		map.put(nombreAtributo, atributo);
		atributosSNG.put(idUnicoServicioNegocio, map);
	}

	/**
	 * Método que nos permite eliminar el atributo cuyo nombre coincide con el
	 * parámetro nombreAtributo recibido por parámetro, de la HashMap de
	 * atributos del contexto y que este asociado a esta orquestación del
	 * servicio de negocio.
	 * 
	 * @param idUnicoServicioNegocio
	 *            String
	 * @param nombreAtributo
	 *            String
	 **/
	public void eliminarAtributoContexto(final String idUnicoServicioNegocio,
			final String nombreAtributo) {

		if (atributosSNG != null
				&& atributosSNG.containsKey(idUnicoServicioNegocio)) {

			final HashMap<String, Object> map = atributosSNG
					.get(idUnicoServicioNegocio);

			if (map != null && map.containsKey(nombreAtributo)) {
				map.remove(nombreAtributo);
				atributosSNG.put(idUnicoServicioNegocio, map);
			}
		}
	}

	/**
	 * Método que nos permite eliminar todos los atributos que se hayan definido
	 * dentro del HashMap de atributos del servicio de negocio, asociados a esta
	 * orquestación concreta. Los atributos de esta orquestación los tenemos
	 * almacenados con la clave del identificador único del servicio de negocio
	 * 
	 * @param idUnicoServicioNegocio
	 *            String --> Identificador concreto de esta instancia de la
	 *            orquestacion de este servicio de negocio
	 **/
	public void eliminarReferenciaServicioNegocio(
			final String idUnicoServicioNegocio) {
		if (atributosSNG != null
				&& atributosSNG.containsKey(idUnicoServicioNegocio)) {
			atributosSNG.remove(idUnicoServicioNegocio);
		}
	}

	/**
	 * Metodo que nos permite eliminar todos los atributos que se hayan definido
	 * dentro del HashMap de atributos del servicio de negocio, asociados a esta
	 * orquestacion concreta. Los atributos de esta orquestacion los tenemos
	 * almacenados con la clave del identificador unico del servicio de negocio
	 * 
	 * @param idUnicoServicioNegocio
	 *            String --> Identificador concreto de esta instancia de la
	 *            orquestacion de este servicio de negocio
	 **/
	public boolean contieneReferenciaServicioNegocio(
			final String idUnicoServicioNegocio) {
		return atributosSNG.containsKey(idUnicoServicioNegocio);
	}

	/**
	 * Metodo que nos permite generar la traza de error y construir el tipo de
	 * Excepcion SNException que trata aquellos errores que no son propios de la
	 * orquestacion ni de la invocacion al servicio IT(estos son devueltos como
	 * excepciones WIException internamente)
	 * 
	 * @param e
	 *            Throwable --> Si no se especifica, se tiene en cuenta el
	 *            mensaje y el errorCode de la causa
	 * @throws SNException
	 **/
	public static void throwSNException(final Throwable e) throws SNException {
		final String errorCode = "";

		generarTrazaError(errorCode, e.getMessage());
		throw new SNException(e);
	}

	/**
	 * Metodo que nos permite generar la traza de error y construir el tipo de
	 * Excepcion SNException que trata aquellos errores que no son propios de la
	 * orquestacion ni de la invocacion al servicio IT(estos son devueltos como
	 * excepciones WIException internamente)
	 * 
	 * @param sn
	 *            SNException --> Si no se especifica, se tiene en cuenta el
	 *            mensaje y el errorCode de la causa
	 * @param nombreClase
	 *            String
	 * @throws SNException
	 **/
	public static void throwSNException(final SNException sn,
			final String nombreClase) throws SNException {
		String errorCode = "";
		if (sn.getCause() instanceof WIException) {
			errorCode = ((WIException) sn.getCause()).getErrorCode();
		}
		final StringBuilder mensaje = new StringBuilder().append(nombreClase)
				.append(".").append(sn.getMessage());
		generarTrazaError(errorCode, mensaje.toString());
		throw sn;
	}

	/**
	 * Metodo que nos permite generar la traza de error y construir el tipo de
	 * Excepcion SNException que trata aquellos errores que no son propios de la
	 * orquestacion ni de la invocacion al servicio IT(estos son devueltos como
	 * excepciones WIException internamente)
	 * 
	 * @param sn
	 *            SNException --> Si no se especifica, se tiene en cuenta el
	 *            mensaje y el errorCode de la causa
	 * @throws SNException
	 **/
	public static void throwSNException(final SNException sn)
			throws SNException {
		String errorCode = "";

		if (sn.getCause() instanceof WIException) {
			errorCode = ((WIException) sn.getCause()).getErrorCode();
		}

		generarTrazaError(errorCode, sn.getMessage());
		throw sn;
	}

	/**
	 * Metodo que nos permite generar la traza de error y construir el tipo de
	 * Excepcion SNException que trata aquellos errores que no son propios de la
	 * orquestacion ni de la invocacion al servicio IT(estos son devueltos como
	 * excepciones WIException internamente)
	 * 
	 * @param mensaje
	 *            String --> Puede venir vacio, si no se especifica valor no se
	 *            anade al mensaje de la excepcion que se lanzara
	 * @param errorCode
	 *            String --> Si recibimos el objeto WIException y tiene
	 *            informado el campo errorCode no se tiene en cuenta
	 * @param nombreClase
	 *            String
	 * @throws SNException
	 **/
	public static void throwSNException(final String mensaje,
			final String errorCode, final String nombreClase)
			throws SNException {
		final StringBuilder mensajeError = new StringBuilder()
				.append(nombreClase).append(".").append(mensaje);
		generarTrazaError(errorCode, mensajeError.toString());
		throw new SNException(mensajeError.toString(), errorCode);
	}

	/**
	 * Metodo que nos permite generar la traza de error y construir el tipo de
	 * Excepcion SNException que trata aquellos errores que no son propios de la
	 * orquestacion ni de la invocacion al servicio IT(estos son devueltos como
	 * excepciones WIException internamente)
	 * 
	 * @param mensaje
	 *            String --> Puede venir vacio, si no se especifica valor no se
	 *            anade al mensaje de la excepcion que se lanzara
	 * @param errorCode
	 *            String --> Si recibimos el objeto WIException y tiene
	 *            informado el campo errorCode no se tiene en cuenta
	 * @throws SNException
	 **/
	public static void throwSNException(final String mensaje,
			final String errorCode) throws SNException {
		generarTrazaError(errorCode, mensaje);
		throw new SNException(mensaje, errorCode);
	}

	/**
	 * Metodo que nos permite generar la traza de error y construir el tipo de
	 * Excepcion SNException que trata aquellos errores que no son propios de la
	 * orquestacion ni de la invocacion al servicio IT(estos son devueltos como
	 * excepciones WIException internamente)
	 * 
	 * @param wi
	 *            WIException --> Si no se especifica, se tiene en cuenta el
	 *            mensaje y el errorCode
	 * @param mensaje
	 *            String --> Puede venir vacio, si no se especifica valor no se
	 *            anade al mensaje de la excepcion que se lanzara
	 * @param errorCode
	 *            String --> Si recibimos el objeto WIException y tiene
	 *            informado el campo errorCode no se tiene en cuenta
	 * @param nombreClase
	 *            String
	 * @throws SNException
	 **/
	public static void throwSNException(final WIException wi,
			final String mensaje, final String errorCode,
			final String nombreClase) throws SNException {
		StringBuilder mensajeBuilder = new StringBuilder().append(nombreClase)
				.append(".");
		if (wi != null) {
			mensajeBuilder = mensajeBuilder
					.append((noEsvacio(mensaje) ? mensaje : "")).append(" \t")
					.append(wi.getMessage());
			generarTrazaError(errorCode, mensajeBuilder.toString());
			throw new SNException(mensajeBuilder.toString(),
					(noEsvacio(wi.getErrorCode()) ? wi.getErrorCode()
							: errorCode));
		} else {
			mensajeBuilder = mensajeBuilder
					.append((noEsvacio(mensaje) ? mensaje : ""));
			generarTrazaError(errorCode, mensajeBuilder.toString());
			throw new SNException(mensajeBuilder.toString(), errorCode);
		}
	}

	/**
	 * Metodo que nos permite generar la traza de error y construir el tipo de
	 * Excepcion SNException que trata aquellos errores que no son propios de la
	 * orquestacion ni de la invocacion al servicio IT(estos son devueltos como
	 * excepciones WIException internamente)
	 * 
	 * @param wi
	 *            WIException --> Si no se especifica, se tiene en cuenta el
	 *            mensaje y el errorCode
	 * @param mensaje
	 *            String --> Puede venir vacio, si no se especifica valor no se
	 *            anade al mensaje de la excepcion que se lanzara
	 * @param errorCode
	 *            String --> Si recibimos el objeto WIException y tiene
	 *            informado el campo errorCode no se tiene en cuenta
	 * @throws SNException
	 **/
	public static void throwSNException(final WIException wi,
			final String mensaje, final String errorCode) throws SNException {
		if (wi != null) {

			final StringBuilder mensajeBuilder = new StringBuilder()
					.append((noEsvacio(mensaje) ? mensaje : "")).append(" \t")
					.append(wi.getMessage());

			generarTrazaError(errorCode, mensajeBuilder.toString());

			throw new SNException(mensajeBuilder.toString(),
					(noEsvacio(wi.getErrorCode()) ? wi.getErrorCode()
							: errorCode));
		} else {
			generarTrazaError(errorCode, mensaje);
			throw new SNException(mensaje, errorCode);
		}
	}

	public void errorInternoSNG(final Exception e,
			final String idUnicoServicioNegocio) throws SNException {
		final Throwable excepcionATratar = obtenerExcepcion(e);

		if (excepcionATratar instanceof WIException) {
			if (eliminarReferenciaServicioNegocioConWIException()) {
				eliminarReferenciaServicioNegocio(idUnicoServicioNegocio);
			}
			errorInvocacionSNG((WIException) excepcionATratar,
					idUnicoServicioNegocio);
		} else {
			eliminarReferenciaServicioNegocio(idUnicoServicioNegocio);
			final SNException sn = obtenerSNException(e);
			throwSNException(sn);
		}
	}

	private Throwable obtenerExcepcion(final Exception e) {
		if ((e instanceof CamelException)
				&& e.getCause() instanceof WIException) {
			return e.getCause();
		}
		return e;
	}

	private SNException obtenerSNException(final Exception e) {
		SNException sn = null;
		final WIException wiGenerica = new WIException(e.getMessage(),
				obtenerCodigoErrorGenericoSNG(), 10);
		if (e instanceof SNException) {
			sn = tratarSNException(e, wiGenerica);
		} else if ((e instanceof CamelException)) {
			sn = tratarCamelException(e, wiGenerica);
		} else {
			wiGenerica.initCause(e);
			sn = new SNException(wiGenerica);
		}
		return sn;
	}

	private SNException tratarCamelException(final Exception e,
			final WIException wiGenerica) {
		SNException sn = null;
		if (e.getCause() != null && (e.getCause() instanceof SNException)) {
			sn = (SNException) e.getCause();
		} else {
			wiGenerica.initCause(e.getCause());
			sn = new SNException(wiGenerica);
		}
		return sn;
	}

	private SNException tratarSNException(final Exception e,
			final WIException wiGenerica) {
		SNException sn = null;
		if (e.getCause() != null && (e.getCause() instanceof WIException)) {
			sn = (SNException) e;
		} else if (e.getCause() == null
				|| !(e.getCause() instanceof WIException)) {
			wiGenerica.initCause(e);
			sn = new SNException(wiGenerica);
		} else {
			sn = new SNException(wiGenerica);
		}
		return sn;
	}

	public abstract void errorInvocacionSNG(final Exception e,
			final String idUnicoServicioNegocio) throws SNException;

	public abstract String obtenerCodigoErrorGenericoSNG();

	public boolean eliminarReferenciaServicioNegocioConWIException() {
		return true;
	}

	/**
	 * Metodo que nos permite generar las trazas de Debug con el formato
	 * especificado
	 * 
	 * @param mensaje
	 *            String
	 * @param nombreClase
	 *            String
	 **/
	public static void generarTrazaDebug(final String mensaje,
			final String nombreClase) throws SNException {
		final StringBuilder mensajeBuilder = new StringBuilder()
				.append(nombreClase).append(".").append(mensaje);
		Trace.log.debug("{}", mensajeBuilder.toString());
	}

	/**
	 * Metodo que nos permite generar las trazas de Debug con el formato
	 * especificado
	 * 
	 * @param mensaje
	 *            String
	 **/
	public static void generarTrazaDebug(final String mensaje)
			throws SNException {
		Trace.log.debug("{}", mensaje);
	}

	/**
	 * Metodo que nos permite generar las trazas de error con el formato
	 * especificado.
	 * 
	 * @param nombreClase
	 *            String
	 * @param mensaje
	 *            String
	 * @param mensajeExcepcion
	 *            String
	 **/
	public static void generarTrazaError(final String mensaje,
			final String mensajeExcepcion, final String nombreClase)
			throws SNException {
		final StringBuilder mensajeBuilder = new StringBuilder()
				.append(nombreClase).append(".").append(mensaje);

		Trace.log.error("{} ERROR: {} ", mensajeBuilder.toString(),
				mensajeExcepcion);
	}

	/**
	 * Metodo que nos permite generar las trazas de error con el formato
	 * especificado.
	 * 
	 * @param mensaje
	 *            String
	 * @param mensajeExcepcion
	 *            String
	 **/
	public static void generarTrazaError(final String mensaje,
			final String mensajeExcepcion) throws SNException {
		Trace.log.error("{} ERROR: {} ", mensaje, mensajeExcepcion);
	}

	/**
	 * Metodo que nos permite generar las trazas de error con el formato
	 * especificado.
	 * 
	 * @param mensaje
	 *            String
	 * @param nombreClase
	 *            String
	 **/
	public static void generarTrazaWarn(final String mensaje,
			final String nombreClase) throws SNException {
		final StringBuilder mensajeBuilder = new StringBuilder()
				.append(nombreClase).append(".").append(mensaje);

		Trace.log.error("WARN: {} ", mensajeBuilder.toString());
	}

	/**
	 * Metodo que nos permite comprobar si todos los digitos del String recibido
	 * por parametro son numeros.
	 * 
	 * @param str
	 *            String
	 * @return boolean
	 **/
	public static boolean isNumeric(final String str) {
		if (str == null) {
			return false;
		}
		final int sz = str.length();
		for (int i = 0; i < sz; i++) {
			if (Character.isDigit(str.charAt(i)) == false) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Metodo que nos permite comprobar si el string recibido por parametro es
	 * null o vacio
	 * 
	 * @param String
	 * @return boolean
	 **/
	public static boolean noEsvacio(final String str) {
		return str != null && str.length() > 0;
	}

	public static boolean esVacio(final String str) {
		return str == null || str.length() == 0;
	}

	public static String eliminarEspaciosEnBlancoDerecha(final String str,
			final String caracterAEliminar) {
		int end;
		if (str == null || (end = str.length()) == 0) {
			return str;
		}
		if (caracterAEliminar == null) {
			while ((end != 0) && Character.isWhitespace(str.charAt(end - 1))) {
				end--;
			}
		} else if (caracterAEliminar.length() == 0) {
			return str;
		} else {
			while ((end != 0)
					&& (caracterAEliminar.indexOf(str.charAt(end - 1)) != -1)) {
				end--;
			}
		}
		return str.substring(0, end);
	}

	public static boolean existeStringEnArray(final String[] listaElementos,
			final String objetoABuscar) {
		if (objetoABuscar == null) {
			return false;
		}
		if (listaElementos != null && listaElementos.length > 0) {
			for (int i = 0; i < listaElementos.length; i++) {
				if (objetoABuscar.equals(listaElementos[i])) {
					return true;
				}
			}
		}
		return false;
	}

	public static String leftPad(final String str, final int tamanoFinalString,
			final char caracterAIncluir) {
		final int pads = tamanoFinalString - str.length();
		if (pads <= 0) {
			return str;
		}
		String caracteres = "";
		for (int i = 0; i < pads; i++) {
			caracteres = caracteres.concat(String.valueOf(caracterAIncluir));
		}
		final String cadena = caracteres.concat(str);

		return cadena;
	}

	/**
	 * <p>
	 * Right pad a String with a specified character.
	 * </p>
	 * 
	 * <p>
	 * The String is padded to the size of <code>size</code>.
	 * </p>
	 * 
	 * <pre>
	 * StringUtils.rightPad(null, *, *)     = null
	 * StringUtils.rightPad("", 3, \'z\')     = "zzz"
	 * StringUtils.rightPad("bat", 3, \'z\')  = "bat"
	 * StringUtils.rightPad("bat", 5, \'z\')  = "batzz"
	 * StringUtils.rightPad("bat", 1, \'z\')  = "bat"
	 * StringUtils.rightPad("bat", -1, \'z\') = "bat"
	 * </pre>
	 * 
	 * @param str
	 *            the String to pad out, may be null
	 * @param size
	 *            the size to pad to
	 * @param padChar
	 *            the character to pad with
	 * @return right padded String or original String if no padding is
	 *         necessary, <code>null</code> if null String input
	 * @since 2.0
	 */
	public static String rightPad(final String str,
			final int tamanoFinalString, final char caracterAIncluir) {

		final int pads = tamanoFinalString - str.length();
		if (pads <= 0) {
			return str;
		}
		String caracteres = "";
		for (int i = 0; i < pads; i++) {
			caracteres = caracteres.concat(String.valueOf(caracterAIncluir));
		}
		final String cadena = str.concat(caracteres);

		return cadena;
	}

	/**
	 * <p>
	 * Elimina todos los espacios en blanco de la cadena recibida por parametro
	 * 
	 * <pre>
	 * StringUtils.deleteWhitespace(null)         = null
	 * StringUtils.deleteWhitespace("")           = ""
	 * StringUtils.deleteWhitespace("abc")        = "abc"
	 * StringUtils.deleteWhitespace("   ab  c  ") = "abc"
	 * </pre>
	 * 
	 * @param str
	 *            the String to delete whitespace from, may be null
	 * @return the String without whitespaces, <code>null</code> if null String
	 *         input
	 */
	public static String deleteWhitespace(final String str) {
		if (esVacio(str)) {
			return str;
		}
		final int sz = str.length();
		final char[] chs = new char[sz];
		int count = 0;
		for (int i = 0; i < sz; i++) {
			if (!Character.isWhitespace(str.charAt(i))) {
				chs[count++] = str.charAt(i);
			}
		}
		if (count == sz) {
			return str;
		}
		return new String(chs, 0, count);
	}
}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);
?>