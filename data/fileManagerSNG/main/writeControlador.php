<?php

	$myfile = fopen($sng->name."_1_Controlador.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_Controlador.java", "w") or die("Unable to open file!");

	$txt = 'package '.$sng->paqueteria.';

import '.$sng->paqueteria.'.utilidades.AbstractControladorSNG_1;
import '.$sng->paqueteria.'.utilidades.ConstantesSNG_1;

public class '.$sng->name.'_1_Controlador
		extends AbstractControladorSNG_1 {

	@Override
	public void transformar(final IContextoSN contextoSN) {
		try {

			generarTrazaDebug(String.format(
					ConstantesSNG_1.DEBUG_INICIO_TRANSFORMAR,
					Thread.currentThread().getStackTrace()[1].getMethodName(),
					this.getClass().getSimpleName()));

			final '.$sng->name.' servicioNegocio = ('.$sng->name.') contextoSN
					.getServicioNegocio();

			transformar(servicioNegocio);

			generarTrazaDebug(String.format(
					ConstantesSNG_1.DEBUG_FIN_TRANSFORMAR,
					Thread.currentThread().getStackTrace()[1].getMethodName(),
					this.getClass().getSimpleName()));
		}


		catch (final Exception e) {
			throwSNException(e.getMessage(),
					ConstantesSNG_1.COD_ERROR_GENERICO, this.getClass()
							.getSimpleName());
		}
	}

	public void transformar(
			final '.$sng->name.' servicioNegocio)
			throws WIException {

		validarCamposEntradaObligatorios(servicioNegocio);
	}

	private void validarCamposEntradaObligatorios(
			final '.$sng->name.' servicioNegocio) {

		/* 
		try {
			if (esVacio(servicioNegocio.getreferenciaExpediente())) {
				throwSNException(String.format(
						ConstantesSNG_1.DES_ERROR_PARAM_ENTRADA,
						ConstantesSNG_1.PARAM_REF_EXP),
						obtenerCodigoErrorGenericoSNG(), this.getClass()
								.getName());
			}
		}

		catch (SNException e) {
			throwSNException(e);
		}*/
	}

	@Override
	public void errorInvocacionSNG(final Exception e,
			final String idUnicoServicioNegocio) throws SNException {

		if (e instanceof WIException) {
			// TO-DO: aplicar el código de error correspondiente
			throwSNException(ConstantesSNG_1.DES_ERROR_WI_EXCEPTION_XXXXXXX,
					ConstantesSNG_1.COD_ERROR_WI_EXCEPTION_XXXXXXX, this
							.getClass().getSimpleName());
		}  else {
			throwSNException(e.getMessage(),
					ConstantesSNG_1.COD_ERROR_GENERICO, this.getClass()
							.getSimpleName());
		}
	}

	@Override
	public String obtenerCodigoErrorGenericoSNG() {
		return ConstantesSNG_1.COD_ERROR_GENERICO;
	}

}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);
?>