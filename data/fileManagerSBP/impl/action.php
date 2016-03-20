<?php

	$importsText = "";
	$resources = "";

	for ($i=0; $i<count($sbp->sngs); $i++) {
		$importsText.='import '.$sbp->subproceso->paqueteria.'.dao.'.$sbp->sngs[$i]->name.'DAO;
import '.$sbp->subproceso->paqueteria.'.dao.dto.'.$sbp->sngs[$i]->name.'InDTO;
import '.$sbp->subproceso->paqueteria.'.dao.dto.'.$sbp->sngs[$i]->name.'OutDTO;
';
		$resources.='
	@Resource
	private '.$sbp->sngs[$i]->name.'DAO '.lcfirst($sbp->sngs[$i]->name).'DAO;
';
	}

	for ($i=0; $i<count($sbp->daos); $i++) {
		$importsText.='import '.$sbp->daos[$i]->groupId.'.dao.'.$sbp->daos[$i]->name.'DAO;
import '.$sbp->daos[$i]->groupId.'.dao.dto.'.$sbp->daos[$i]->name.'InDTO;
import '.$sbp->daos[$i]->groupId.'.dao.dto.'.$sbp->daos[$i]->name.'OutDTO;
';
		$resources.='
	@Resource
	private '.$sbp->daos[$i]->name.'DAO '.lcfirst($sbp->daos[$i]->name).'DAO;
';
	}


	for ($i=0; $i<count($sbp->views); $i++) {
		$importsText.='import '.$sbp->subproceso->paqueteria.'.flow.dto.'.$sbp->views[$i].';
import '.$sbp->subproceso->paqueteria.'.flow.assemble.'.$sbp->views[$i].'Assembler;
';
	}

	for ($i=0; $i<count($sbp->sngDaos); $i++) {
		if ($sbp->sngDaos[$i]->beanAuxiliar != null && $sbp->sngDaos[$i]->beanAuxiliar != ""){
			$importsText.='import '.$sbp->subproceso->paqueteria.'.flow.dto.'.$sbp->sngDaos[$i]->beanAuxiliar.';
';
		}
	}


	for ($i=0; $i<count($sbp->models); $i++) {
		$importsText.='import '.$sbp->subproceso->paqueteria.'.flow.dto.'.$sbp->models[$i].';
';
	}



	$corpse = "";

	for ($i=0; $i<count($sbp->arrayActionsAux); $i++) {

		if ($sbp->arrayActionsAux[$i]->sngDao->rellamada){

		//CORPSE DE LOS EVENTS
		$corpse .= 'public Event '.$sbp->arrayActionsAux[$i]->name.'(
			final '.$sbp->arrayActionsAux[$i]->sngDao->beanAuxiliar.' beanAuxiliar,
			final '.$sbp->arrayActionsAux[$i]->view.' view) {

		log.debug("Inicio - '.$sbp->arrayActionsAux[$i]->name.'");

		final '.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO inDTO = new '.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO();

		final String claveRellamadaMasExpedientes = "";// Primera llamada vacía
		mapeoModeloInDTO(claveRellamadaMasExpedientes, inDTO);

		final '.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO outDTO = '.$sbp->arrayActionsAux[$i]->sngDao->name.'(inDTO, view);

		if (StringUtils.isBlank(view.getCodigoError())) {
			comprobarRellamada(beanAuxiliar, outDTO);
			mapearOutDTOView(view, outDTO);
		}

		log.debug("Fin - '.$sbp->arrayActionsAux[$i]->name.'");

		return success();
	}


	public Event '.$sbp->arrayActionsAux[$i]->nameRellamada.'(
			final '.$sbp->arrayActionsAux[$i]->sngDao->beanAuxiliar.' beanAuxiliar,
			final '.$sbp->arrayActionsAux[$i]->view.' view) {

		log.debug("Inicio - '.$sbp->arrayActionsAux[$i]->nameRellamada.'");

		final '.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO inDTO = new '.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO();

		mapeoModeloInDTO(beanAuxiliar.getClaveRellamadaMasExpedientes(), inDTO);

		final '.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO outDTO = '.$sbp->arrayActionsAux[$i]->sngDao->name.'(
				inDTO, view);

		if (StringUtils.isBlank(view.getCodigoError())) {
			comprobarRellamada(beanAuxiliar, outDTO);
			mapearOutDTOView(view, outDTO);
		}

		log.debug("Fin - '.$sbp->arrayActionsAux[$i]->nameRellamada.'");

		return success();
	}
	
	private void mapeoModeloInDTO(final String claveRellamada,
			'.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO inDTO) {

		log.debug("Inicio - mapeoModeloInDTO");

		DatosBasicosCliente personalData = (DatosBasicosCliente) executionContextHolder.getExecutionContext().getCustomerPersonalData();
		String identificadorCliente = personalData.getIdentificadorCliente();

//		inDTO.setClaveRellamadaExpediente(claveRellamada);
//		inDTO.setIdentificadorCliente(identificadorCliente);

		log.debug("Fin - mapeoModeloInDTO");
	}
	
	private '.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO '.$sbp->arrayActionsAux[$i]->sngDao->name.'(
			final '.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO inDTO,
			final '.$sbp->arrayActionsAux[$i]->view.' '.$sbp->arrayActionsAux[$i]->view.') {

		log.debug("Inicio - '.$sbp->arrayActionsAux[$i]->sngDao->name.'(inDTO, '.$sbp->arrayActionsAux[$i]->view.')");

		'.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO outDto = new '.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO();
		try {
			outDto = '.$sbp->arrayActionsAux[$i]->sngDao->name.'DAO
					.'.$sbp->arrayActionsAux[$i]->name.'(inDTO);
		} catch (final ServiceException e) {
			if (e.getErrorCode().equals(Constantes.COD_ERROR_CQ0078)) {
				'.$sbp->arrayActionsAux[$i]->view.'
						.setCodigoError(e.getErrorCode());
				'.$sbp->arrayActionsAux[$i]->view.'
						.setResolucion(e.getMessage());
			} else {
				lanzarExcepcionCapaSubproceso(Constantes.COD_ERROR_GENERICO,
						Constantes.DES_ERROR_GENERICO, e);
			}
		}

		log.debug("Fin - '.$sbp->arrayActionsAux[$i]->sngDao->name.'(inDTO, '.$sbp->arrayActionsAux[$i]->view.')");
		return outDto;
	}
	
	private void comprobarRellamada(
			final '.$sbp->arrayActionsAux[$i]->sngDao->beanAuxiliar.' beanAuxiliar,
			'.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO outDTO) {

		log.debug("Inicio - comprobarRellamada");
		log.debug("outDTO: " + outDTO);
		log.debug("beanAuxiliar: " + beanAuxiliar);

//		if (outDTO.isIndicadorMasExpedientes()) {
//			beanAuxiliar.setClaveRellamadaMasExpedientes(outDTO
//					.getClaveRellamadaOut());
//		}

		log.debug("Fin - comprobarRellamada");

	}
	
	
	private void mapearOutDTOView(
			final '.$sbp->arrayActionsAux[$i]->view.' view,
			final '.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO outDTO) {

		try {
			ObjectAssembler
					.from(outDTO)
					.to(view)
					.using('.$sbp->arrayActionsAux[$i]->view.'Assembler.class)
					.assembly();

		} catch (final AssembleException e) {
			lanzarExcepcionCapaSubproceso(
					Constantes.COD_ERROR_GENERICO,
					"Error ensamblando la vista '.$sbp->arrayActionsAux[$i]->view.'",
					e);
		}
	}

';
	}else{

		$corpse .= 'public Event '.$sbp->arrayActionsAux[$i]->name.'(
			final '.$sbp->arrayActionsAux[$i]->view.' view) {

		log.debug("Inicio - '.$sbp->arrayActionsAux[$i]->name.'");

		final '.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO inDTO = new '.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO();

		mapeoModeloInDTO(inDTO);

		final '.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO outDTO = '.$sbp->arrayActionsAux[$i]->sngDao->name.'(inDTO, view);

		if (StringUtils.isBlank(view.getCodigoError())) {
			mapearOutDTOView(view, outDTO);
		}

		log.debug("Fin - '.$sbp->arrayActionsAux[$i]->name.'");

		return success();
	}

	
	private void mapeoModeloInDTO(
			'.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO inDTO) {

		log.debug("Inicio - mapeoModeloInDTO");

		DatosBasicosCliente personalData = (DatosBasicosCliente) executionContextHolder.getExecutionContext().getCustomerPersonalData();
		String identificadorCliente = personalData.getIdentificadorCliente();

//		inDTO.setIdentificadorCliente(identificadorCliente);

		log.debug("Fin - mapeoModeloInDTO");
	}
	
	private '.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO '.$sbp->arrayActionsAux[$i]->sngDao->name.'(
			final '.$sbp->arrayActionsAux[$i]->sngDao->name.'InDTO inDTO,
			final '.$sbp->arrayActionsAux[$i]->view.' '.$sbp->arrayActionsAux[$i]->view.') {

		log.debug("Inicio - '.$sbp->arrayActionsAux[$i]->sngDao->name.'(inDTO, '.$sbp->arrayActionsAux[$i]->view.')");

		'.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO outDto = new '.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO();
		try {
			outDto = '.$sbp->arrayActionsAux[$i]->sngDao->name.'DAO
					.'.$sbp->arrayActionsAux[$i]->name.'(inDTO);
		} catch (final ServiceException e) {
			if (e.getErrorCode().equals(Constantes.COD_ERROR_CQ0078)) {
				'.$sbp->arrayActionsAux[$i]->view.'
						.setCodigoError(e.getErrorCode());
				'.$sbp->arrayActionsAux[$i]->view.'
						.setResolucion(e.getMessage());
			} else {
				lanzarExcepcionCapaSubproceso(Constantes.COD_ERROR_GENERICO,
						Constantes.DES_ERROR_GENERICO, e);
			}
		}

		log.debug("Fin - '.$sbp->arrayActionsAux[$i]->sngDao->name.'(inDTO, '.$sbp->arrayActionsAux[$i]->view.')");
		return outDto;
	}
	
	
	private void mapearOutDTOView(
			final '.$sbp->arrayActionsAux[$i]->view.' view,
			final '.$sbp->arrayActionsAux[$i]->sngDao->name.'OutDTO outDTO) {

		try {
			ObjectAssembler
					.from(outDTO)
					.to(view)
					.using('.$sbp->arrayActionsAux[$i]->view.'Assembler.class)
					.assembly();

		} catch (final AssembleException e) {
			lanzarExcepcionCapaSubproceso(
					Constantes.COD_ERROR_GENERICO,
					"Error ensamblando la vista '.$sbp->arrayActionsAux[$i]->view.'",
					e);
		}
	}

';




	}


	}




//CONSTRUCCION DEL FICHERO CON LAS VARIABLES QU4E SE HAN MONTADO ANTEIRORMENTE
	$myfile = fopen("flow/".$sbp->subproceso->name."Action.java", "w") or die("Unable to open file!");


	$txt = 'package '.$sbp->subproceso->paqueteria.'.flow;


import javax.annotation.Resource;

import lombok.extern.slf4j.Slf4j;

import org.apache.commons.lang3.StringUtils;
import org.springframework.stereotype.Service;
import org.springframework.webflow.action.MultiAction;
import org.springframework.webflow.execution.Event;

import com.custom.arq.ioi.commons.bsinvoker.exceptions.ServiceException;
import com.custom.arq.ioi.commons.channel.assemble.AssembleException;
import com.custom.arq.ioi.commons.channel.assemble.ObjectAssembler;
import com.custom.arq.ioi.commons.execution.context.ExecutionContextHolder;
import com.custom.arq.ioi.flows.core.exceptions.GenericFlowException;
import com.custom.ioi.dto.usuario.DatosBasicosCliente;


'.$importsText.'


import '.$sbp->subproceso->paqueteria.'.flow.utilidades.Constantes;

import com.gfi.webIntegrator.WIException;

@Slf4j
@Service
public class '.$sbp->subproceso->name.'Action extends MultiAction {

	@Resource
	private ExecutionContextHolder executionContextHolder;

	'.$resources.'


	'.$corpse.'


	private void lanzarExcepcionCapaSubproceso(final String codigo,
			final String mensaje, final Exception exception) {

		throw new GenericFlowException(codigo, mensaje, exception);
	}

}
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>