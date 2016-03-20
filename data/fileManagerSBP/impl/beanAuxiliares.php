<?php

	for ($i=0; $i<count($sbp->sngDaos); $i++) {

		if ($sbp->sngDaos[$i]->beanAuxiliar != null && $sbp->sngDaos[$i]->beanAuxiliar != ""){

			$myfile = fopen("flow/dto/".$sbp->sngDaos[$i]->beanAuxiliar.".java", "w") or die("Unable to open file!");




			$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.dto;

import java.io.Serializable;

import lombok.Data;

@Data
public class '.$sbp->sngDaos[$i]->beanAuxiliar.' implements Serializable {

	//private static final long serialVersionUID = 4784147766662248565L;
	private String claveRellamada;

}
';




			if (fwrite($myfile, $txt))
				echo true;
			else
				echo false;

			fclose($myfile);

		}
	}




?>