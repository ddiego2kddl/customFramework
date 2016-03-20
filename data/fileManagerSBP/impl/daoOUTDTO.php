<?php

	for ($i=0; $i<count($sbp->sngs); $i++) {



			$myfile = fopen("dao/dto/".$sbp->sngs[$i]->name."OutDTO.java", "w") or die("Unable to open file!");




			$txt = 'package '.$sbp->subproceso->paqueteria.'.dao.dto;

import java.io.Serializable;

import lombok.Data;

@Data
public class '.$sbp->sngs[$i]->name.'OutDTO implements Serializable{

//	private static final long serialVersionUID = 4749493638171238479L;

//	private boolean indicadorMasLiquidacionesRepartoMargenes;
//	private String claveRellamadaLiquidacionesRepartoMargenesOut;
	
}


';




			if (fwrite($myfile, $txt))
				echo true;
			else
				echo false;

			fclose($myfile);

	}


?>