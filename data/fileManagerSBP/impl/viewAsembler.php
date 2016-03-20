<?php

	for ($i=0; $i<count($sbp->ius); $i++) {

			$paqueteriaOUTDTO = $sbp->ius[$i]->outDTO->groupId;
			if ($paqueteriaOUTDTO == "com.custom.sn")
				$paqueteriaOUTDTO = $sbp->subproceso->paqueteria.".dao";

			$myfile = fopen("flow/assemble/".$sbp->ius[$i]->view."Assembler.java", "w") or die("Unable to open file!");




			$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.asseble;


import java.util.ArrayList;
import java.util.List;

import javax.annotation.Resource;

import org.javatuples.Unit;
import org.springframework.stereotype.Service;

import '.$paqueteriaOUTDTO.'.dto.'.$sbp->ius[$i]->outDTO->name.'OutDTO;
import '.$sbp->subproceso->paqueteria.'.flow.dto.'.$sbp->ius[$i]->view.';

@Service
public class '.$sbp->ius[$i]->view.'Assembler
		implements
		MultipleTypeAssembler<'.$sbp->ius[$i]->view.', Unit<'.$sbp->ius[$i]->outDTO->name.'OutDTO>> {

	@Resource
	private ExecutionContextHolder executionContextHolder;
	
	@Override
	public void assembly(
			final Unit<'.$sbp->ius[$i]->outDTO->name.'OutDTO> source,
			final '.$sbp->ius[$i]->view.' target)
			throws AssembleException {
		if (source == null) {
			throw new AssembleException("La fuente no puede ser nula");
		}
		if (target == null) {
			throw new AssembleException("El objetivo no puede ser nulo");
		}
		map(source, target);

	}

	@Override
	public '.$sbp->ius[$i]->view.' assembly(
			final Unit<'.$sbp->ius[$i]->outDTO->name.'OutDTO> source)
			throws AssembleException {
		if (source == null) {
			throw new AssembleException("La fuente no puede ser nula");
		}
		final '.$sbp->ius[$i]->view.' target = new '.$sbp->ius[$i]->view.'();
		map(source, target);
		return target;
	}

	private void map(
			final Unit<'.$sbp->ius[$i]->outDTO->name.'OutDTO> source,
			final '.$sbp->ius[$i]->view.' target) {

		'.$sbp->ius[$i]->outDTO->name.'OutDTO outSource = source.getValue0();
	/*	List<com.custom.ioi.financiacion.confirmingcliente.expedientes.consultar.dao.dto.ExpedienteConfirmingCliente> sourceExpedientes = source.getValue0().getExpedientesConfirmingCliente();
		
		List<ExpedienteConfirmingCliente> expedientesConfirmingCliente = new ArrayList<ExpedienteConfirmingCliente>();
		
		for (final com.custom.ioi.financiacion.confirmingcliente.expedientes.consultar.dao.dto.ExpedienteConfirmingCliente expedienteConfirmingCliente : sourceExpedientes) {
			ExpedienteConfirmingCliente expediente = new ExpedienteConfirmingCliente();
			expediente.setCodigoSituacionExpedienteConfirming(expedienteConfirmingCliente.getCodigoSituacionExpedienteConfirming());
			expediente.setIdentificadorExpedienteConfirming(expedienteConfirmingCliente.getIdentificadorExpedienteConfirming());
			expedientesConfirmingCliente.add(expediente);
		}
		
		target.setExpedientesConfirmingCliente(expedientesConfirmingCliente);
		
		DatosBasicosCliente personalData = (DatosBasicosCliente) executionContextHolder.getExecutionContext().getCustomerPersonalData();
		target.setIdentificadorClienteConfirming(personalData.getIdentificadorCliente());
		target.setNombreClienteConfirming(personalData.getNombreRazonSocial());
		target.setIndicadorMasExpedientes(outSource.isIndicadorMasExpedientes());*/

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