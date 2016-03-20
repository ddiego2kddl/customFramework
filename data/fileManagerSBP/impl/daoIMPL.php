<?php

	for ($i=0; $i<count($sbp->sngs); $i++) {



			$myfile = fopen("dao/".$sbp->sngs[$i]->name."DAOImpl.java", "w") or die("Unable to open file!");




			$txt = 'package '.$sbp->subproceso->paqueteria.'.dao;

import javax.annotation.Resource;

import lombok.extern.slf4j.Slf4j;

import org.springframework.stereotype.Service;

import '.$sbp->subproceso->paqueteria.'.dao.dto.'.$sbp->sngs[$i]->name.'InDTO;
import '.$sbp->subproceso->paqueteria.'.dao.dto.'.$sbp->sngs[$i]->name.'OutDTO;

import es.cajamadrid.servicios.SN.'.$sbp->sngs[$i]->name.'SNG.'.$sbp->sngs[$i]->name.'SNG;

@Slf4j
@Service
public class '.$sbp->sngs[$i]->name.'DAOImpl implements '.$sbp->sngs[$i]->name.'DAO{

	public final static String COD_ERROR_GENERICO = "EG0001";

	@Resource
	private BisaClientFactory bisaClientFactory;
	
	public '.$sbp->sngs[$i]->name.'OutDTO '.$sbp->sngs[$i]->name.'(			
			'.$sbp->sngs[$i]->name.'InDTO inDTO) {
		
		'.$sbp->sngs[$i]->name.'OutDTO outDTO = null;
		
		log.debug("Inicio -> '.$sbp->sngs[$i]->name.'OutDTO()");

		comprobarCamposObligatoriosInDTO(inDTO);
		
		try {

			'.$sbp->sngs[$i]->name.'SNG servicioNegocio = ('.$sbp->sngs[$i]->name.'SNG) this.bisaClientFactory
					.getBisaClient('.$sbp->sngs[$i]->name.'SNG.class);

			mapearEntradaSN(servicioNegocio, inDTO);

			this.bisaClientFactory.executeBisaClient(servicioNegocio);

			outDTO = obtenerSalidaDAO(servicioNegocio);
		
		} catch (BisaExecutionException be) {
			log.error("Entrada --> "+inDTO+"\nExcepcion --> ", be);
			throw new ServiceException(be.getErrorCode(), be);			
		} catch (final WIException wi) {
			log.error("Entrada --> "+inDTO+"\nExcepcion --> ", wi);
			throw new ServiceException(wi.getErrorCode(), wi);
		}

		log.debug("Fin ->  '.$sbp->sngs[$i]->name.'OutDTO()");		
		
		return outDTO;
	}

	private void comprobarCamposObligatoriosInDTO(
			'.$sbp->sngs[$i]->name.'InDTO inDTO) {
		if (inDTO == null) {
			throw new ServiceException(COD_ERROR_GENERICO,
					"DTO de entrada es nulo");
		}

//		if (inDTO.getIdentificadorCliente() == null) {
//			throw new ServiceException(COD_ERROR_GENERICO,
//					"El atributo identificadorCliente del DTO de entrada es nulo");
//		}		
//		
//		if (inDTO.getIdentificadorExpedienteConfirming() == null) {
//			throw new ServiceException(COD_ERROR_GENERICO,
//					"El atributo identificadorExpedienteConfirming del DTO de entrada es nulo");
//		}
	}
	
	private void mapearEntradaSN(
			'.$sbp->sngs[$i]->name.'SNG servicioNegocio,
			'.$sbp->sngs[$i]->name.'InDTO inDTO) {
//		servicioNegocio.setidentificadorCliente(inDTO.getIdentificadorCliente());
//		servicioNegocio.setidentificadorExpedienteConfirming(inDTO.getIdentificadorExpedienteConfirming());
//		servicioNegocio.setclaveRellamadaLiquidacionesRepartoMargenes(inDTO.getClaveRellamadaLiquidacionesRepartoMargenes());
	}
	
	private '.$sbp->sngs[$i]->name.'OutDTO obtenerSalidaDAO('.$sbp->sngs[$i]->name.'SNG servicioNegocio) throws WIException {
		final '.$sbp->sngs[$i]->name.'OutDTO outDTO = new '.$sbp->sngs[$i]->name.'OutDTO();
//		outDTO.setIndicadorMasLiquidacionesRepartoMargenes(servicioNegocio.getindicadorMasLiquidacionesRepartoMargenes());
//		outDTO.setClaveRellamadaLiquidacionesRepartoMargenesOut(servicioNegocio.getclaveRellamadaLiquidacionesRepartoMargenesOut());
//		
//		VectorLiquidacionesRepartoMargenes vertorSN = servicioNegocio.getliquidacionesRepartoMargenes();
//		List<LiquidacionRepartoMargenes> liquidacionRepartoMargenes = new ArrayList<LiquidacionRepartoMargenes>();
//		
//		for (int i=0; i< vertorSN.size(); i++) {
//			es.cajamadrid.servicios.SN.'.$sbp->sngs[$i]->name.'SNG.LiquidacionRepartoMargenes elementAt = vertorSN.getLiquidacionRepartoMargenesAt(i);
//			LiquidacionRepartoMargenes liqRepartoMargenes = new LiquidacionRepartoMargenes();
//			liqRepartoMargenes.setFechaLiquidacion(Utilidades.getFecha(elementAt.getfechaLiquidacionBISA()));
//			liqRepartoMargenes.setImporteLiquidacion(Utilidades.getImporte(elementAt.getimporteLiquidacionBISA()));
//			liqRepartoMargenes.setNumeroLiquidacion(elementAt.getidentificadorLiquidacion());
//			liqRepartoMargenes.setTipoLiquidacion(elementAt.getcodigoTipoLiquidacion());
//			liquidacionRepartoMargenes.add(liqRepartoMargenes);
//		}
//		
//		outDTO.setLiquidacionRepartoMargenes(liquidacionRepartoMargenes);
		
		
		return outDTO;
	}
}
';




			if (fwrite($myfile, $txt))
				echo true;
			else
				echo false;

			fclose($myfile);

	}


?>