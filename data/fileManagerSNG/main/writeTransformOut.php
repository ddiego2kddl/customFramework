<?php

	
	$myfile = fopen($sng->name."_1_TransformOut_".$sng->dependencia.".java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformOut_".$sng->dependencia.".java", "w") or die("Unable to open file!");

	$txt = 'package '.$sng->paqueteria.';

import '.$sng->paqueteria.'.utilidades.AbstractControladorSNG_1;
import '.$sng->paqueteria.'.utilidades.ConstantesSNG_1;

public class '.$sng->name.'_1_TransformOut_'.$sng->dependencia.'
		extends TransformerBaseOut {

	@Override
	public void transformar(final IContextoSN contextoSN) {

		AbstractControladorSNG_1.generarTrazaDebug(String.format(
				ConstantesSNG_1.DEBUG_INICIO_TRANSFORMAR, Thread
						.currentThread().getStackTrace()[1].getMethodName(),
				this.getClass().getSimpleName()));

		final '.$sng->dependencia.' servicioIT = ('.$sng->dependencia.') contextoSN
				.getCurrentItService();

		final '.$sng->name.' servicioNegocio = ('.$sng->name.') contextoSN
				.getServicioNegocio();

		transformar(servicioIT, servicioNegocio);

		AbstractControladorSNG_1.generarTrazaDebug(String.format(
				ConstantesSNG_1.DEBUG_FIN_TRANSFORMAR, Thread.currentThread()
						.getStackTrace()[1].getMethodName(), this.getClass()
						.getSimpleName()));
	}

	private void transformar(
			final '.$sng->dependencia.' servicioIT,
			final '.$sng->name.' servicioNegocio) {

		//servicioNegocio.setXXXXXX(servicioIT.getXXXXXX());
		componerVector(servicioIT, servicioNegocio);
	}

	private void componerVector(final '.$sng->dependencia.' servicioIT,
			'.$sng->name.' servicioNegocio) {

		//boolean hayMas = false; // Indicador más elementos

		/*
		try {

			//OBTENER VECTOR DESDE SERVICIOIT
			//final Vector'.$sng->dependencia.'_TablaExpedientestabexp vectorServicioIT = servicioIT.getTablaExpedientestabexp();


			//INICIALIZAR VECTOR DEL SERVICIONEGOCIO
			//VectorDocumentosExpedienteConfirming vector = new VectorDocumentosExpedienteConfirming();

			hayMas = servicioIT.getCodFinPaginacSNcofipa() == ConstantesSNG_1.INDICADOR_CONTROL_TRUE;

			for (int i = 0; i < vectorServicioIT.size(); i++) {
				//OBJETO DEL VECTOR DEL SERVICIONEGOCIO
				//DocumentoExpedienteConfirming documento = new DocumentoExpedienteConfirming();

				//OBJETO DEL VECTOR SERVICIOIT
				//final Struct'.$sng->dependencia.'_TablaExpedientestabexp struct = vectorServicioIT.getStruct'.$sng->dependencia.'_TablaExpedientestabexpAt(i);

				//MAPEAR-SETEAR EL OBJETO DEL SERVICIOIT EN EL OBJETO DEL VECTOR SERVICIONEGOCIO
				//documento.settipoDocumento(struct.getIndicadorOrdenPagoNotaDeAbonoindop2());
				//documento.setnumeroFactura(struct.getCodigoExternoDeDocumentocoexd2());
				
				//SETEAR ELEMENTO AL VECTOR, SE COMPORTA COMO EL ADD
				//vector.setDocumentoExpedienteConfirming(documento);
			}


			//VER CONDICIONES SI HAY RELLAMADA EN EL DDT --> SINO HAY RELLAMADA ELIMINAR ESTO
			if (hayMas) {
				//Integer ultimaPosicion = vectorServicioIT.size() - 1;

				//servicioNegocio.setclaveRellamadaDocumentosExpedienteOut(String.valueOf(vectorServicioIT.getStruct'.$sng->dependencia.'_TablaExpedientestabexpAt(ultimaPosicion).getNumeroInternoDelDocumentonuind22()));

			} else {
				//servicioNegocio.setclaveRellamadaDocumentosExpedienteOut("");
			}

			//SETEAR ATRIBUTOS DEL SERVICIODENEGOCIO DESDE EL SERVICIO IT
			//servicioNegocio.setindicadorMasDocumentosExpediente(hayMas);
			//servicioNegocio.setnombreCliente(servicioIT.getNombreClienteExpedientenoclex());
		
			//SETEAR VECTOR CREADO EN EL SERVICIO DE NEGOCIO
			//servicioNegocio.setdocumentosExpedienteConfirming(vector);

		} catch (final WIException wi) {
			AbstractControladorSNG_1
					.throwSNException(
							wi,
							"TransformOut_struct.'.$sng->dependencia.' - transformar() --> componerVector()",
							ConstantesSNG_1.COD_ERROR_PTE);
		}
		*/
	}
}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);
?>