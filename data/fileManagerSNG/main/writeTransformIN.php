<?php

	$myfile = fopen($sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");

	$txt = 'package '.$sng->paqueteria.';

import '.$sng->paqueteria.'.utilidades.AbstractControladorSNG_1;
import '.$sng->paqueteria.'.utilidades.ConstantesSNG_1;
public class '.$sng->name.'_1_TransformIn_'.$sng->dependencia.'
		extends TransformerBaseIn {

	@Override
	public void transformar(final IContextoSN contextoSN) {

		AbstractControladorSNG_1.generarTrazaDebug(String.format(
				ConstantesSNG_1.DEBUG_INICIO_TRANSFORMAR, Thread
						.currentThread().getStackTrace()[1].getMethodName(),
				this.getClass().getSimpleName()));

		final '.$sng->name.' servicioNegocio = ('.$sng->name.') contextoSN
				.getServicioNegocio();

		final '.$sng->dependencia.' servicioIT = obtenerNuevaInstancia'.$sng->dependencia.'();

		contextoSN.setCurrentItService(servicioIT);

		super.transformCabeceraNegocio(contextoSN);

		transformar(servicioNegocio, servicioIT);

		AbstractControladorSNG_1.generarTrazaDebug(String.format(
				ConstantesSNG_1.DEBUG_FIN_TRANSFORMAR, Thread.currentThread()
						.getStackTrace()[1].getMethodName(), this.getClass()
						.getSimpleName()));
	}

	private '.$sng->dependencia.' obtenerNuevaInstancia'.$sng->dependencia.'() {
		'.$sng->dependencia.' servicioIT = null;

		try {
			servicioIT = new '.$sng->dependencia.'();
		} catch (final WIException e) {
			AbstractControladorSNG_1.throwSNException(e, Thread.currentThread()
					.getStackTrace()[1].getMethodName(),
					ConstantesSNG_1.COD_ERROR_GENERICO);
		}

		return servicioIT;
	}

	 public void transformar(
			final '.$sng->name.' servicioNegocio,
			final '.$sng->dependencia.' servicioIT) {

        try {
            //cargarCabeceraFuncional(servicioIT, servicioNegocio);
            cargarCabeceraAplicacion(servicioIT, servicioNegocio);
        	cargarParteEspecifica(servicioNegocio, servicioIT);
        } catch (final WIException e) {
            AbstractControladorSNG_1.throwSNException(e, Thread.currentThread()
                    .getStackTrace()[1].getMethodName(),
                    ConstantesSNG_1.COD_ERROR_GENERICO);
        }
        
    }

    private void cargarCabeceraFuncional(final '.$sng->dependencia.' servicioIT, final '.$sng->name.' servicioSNG) throws WIException {
        StructCabeceraFuncionalPeticion cabeceraFuncionalPeticion = servicioIT.getcabeceraFuncionalPeticion();
        if (cabeceraFuncionalPeticion == null) {
            cabeceraFuncionalPeticion = new StructCabeceraFuncionalPeticion();
        }
        cabeceraFuncionalPeticion.setCOFRAQ(ConstantesSNG_1.DATA_COFRAQ);

        servicioIT.setcabeceraFuncionalPeticion(cabeceraFuncionalPeticion);
    }

	private void cargarCabeceraAplicacion('.$sng->dependencia.' servicioIT,
			'.$sng->name.' servicioSNG) {

		StructCabeceraAplicacion'.$sng->dependencia.' cabAplicacion = new StructCabeceraAplicacion'.$sng->dependencia.'();
		servicioIT.setcabeceraAplicacion(cabAplicacion);
	}



	private void cargarParteEspecifica(
			final '.$sng->name.' servicioNegocio,
			final '.$sng->dependencia.' servicioIT) throws WIException {
		
		//Setear servioIT en Servicio de negocio -Ejemplo-
		//servicioIT.setNumeroDeExpedientenuexp2(Utilidades.parseStringToLong(servicioNegocio.getreferenciaExpediente()));
		
	}

}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);
?>