<?php

	for ($i=0; $i<count($sbp->views); $i++) {


			$myfile = fopen("flow/dto/".$sbp->views[$i].".java", "w") or die("Unable to open file!");




			$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.dto;

import java.io.Serializable;
import java.util.List;

import lombok.Data;
@Data
public class '.$sbp->views[$i].' extends BaseView implements Serializable {

	//private static final long serialVersionUID = -1594600894883501017L;
	
	//private Boolean indicadorMasXXXXX;

}
';




			if (fwrite($myfile, $txt))
				echo true;
			else
				echo false;

			fclose($myfile);

	}




?>