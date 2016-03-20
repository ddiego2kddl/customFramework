<?php
	
	$myfile = fopen("exception/SNExceptionMatcher.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.exception;

import org.hamcrest.Description;
import org.hamcrest.Matcher;
import org.hamcrest.TypeSafeMatcher;

public class SNExceptionMatcher extends TypeSafeMatcher<SNException> {

	private final String mensajeContiene;
	private final String errorCode;

	public SNExceptionMatcher(final String errorCode) {
		this.errorCode = errorCode;
		this.mensajeContiene = null;
	}

	public SNExceptionMatcher(final String errorCode, final String parteMensaje) {
		this.errorCode = errorCode;
		this.mensajeContiene = parteMensaje;
	}

	public void describeTo(final Description description) {
		description.appendText("La excepcion esperada debe tener ErrorCode: \'");
		description.appendText(String.valueOf(errorCode));
		description.appendText("\'. ");

		if (mensajeContiene != null) {
			description.appendText("Y su mensaje de error debe contener: \'");
			description.appendText(String.valueOf(mensajeContiene));
			description
					.appendText("\'. (Esta validacion no sensible a mayusculas y minusculas) ");
		}
	}

	@Override
	public boolean matchesSafely(final SNException e) {
		boolean esCorrecto = true;
		if (e.getCause() != null && e.getCause() instanceof WIException) {

			if (e.getCause() == null
					|| !errorCode.equals(((WIException) e.getCause())
							.getErrorCode())) {
				esCorrecto = false;
			}
			if (mensajeContiene != null
					&& !e.getMessage().toUpperCase()
							.contains(mensajeContiene.toUpperCase())) {
				esCorrecto = false;
			}
		} else {
			esCorrecto = false;
		}
		return esCorrecto;
	}

	public static Matcher<?> tieneCodigoError(final String errorCode) {
		return new SNExceptionMatcher(errorCode);
	}

	public static Matcher<?> tieneCodigoErrorYMensajeContiene(
			final String errorCode, final String contiene) {
		return new SNExceptionMatcher(errorCode, contiene);
	}

}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>