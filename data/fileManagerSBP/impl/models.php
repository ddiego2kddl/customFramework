<?php

	for ($i=0; $i<count($sbp->models); $i++) {


			$myfile = fopen("flow/dto/".$sbp->models[$i].".java", "w") or die("Unable to open file!");




			$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.dto;

import java.io.Serializable;

import lombok.Data;

@Data
public class '.$sbp->models[$i].' implements Serializable {

	//private static final long serialVersionUID = -7087913675179937587L;
	
	//private String identificadorExpedienteConfirming;

}
';




			if (fwrite($myfile, $txt))
				echo true;
			else
				echo false;

			fclose($myfile);

	}




?>