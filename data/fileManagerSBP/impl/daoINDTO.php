<?php

	for ($i=0; $i<count($sbp->sngs); $i++) {



			$myfile = fopen("dao/dto/".$sbp->sngs[$i]->name."InDTO.java", "w") or die("Unable to open file!");




			$txt = 'package '.$sbp->subproceso->paqueteria.'.dao.dto;
import java.io.Serializable;

import lombok.Data;

@Data
public class '.$sbp->sngs[$i]->name.'InDTO implements Serializable{

//	private static final long serialVersionUID = -7309303622334971978L;
	
//	private String identificadorCliente;
//	private String identificadorExpedienteConfirming;
//	private String claveRellamadaLiquidacionesRepartoMargenes;
}


';




			if (fwrite($myfile, $txt))
				echo true;
			else
				echo false;

			fclose($myfile);

	}


?>