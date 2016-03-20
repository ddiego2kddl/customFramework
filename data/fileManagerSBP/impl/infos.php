<?php

	for ($i=0; $i<count($sbp->infos); $i++) {

		$myfile = fopen("flow/info/".$sbp->infos[$i].".java", "w") or die("Unable to open file!");


		$txt = 'package '.$sbp->subproceso->paqueteria.'.flow.info;
import java.io.Serializable;

import lombok.Data;

//import com.custom.ioi.dto.tipo.Fecha;
//import com.custom.ioi.dto.tipo.ImporteMonetario;

@Data
public class '.$sbp->infos[$i].' implements Serializable {

	//private static final long serialVersionUID = 6956730205777312759L;
	
	//private String string;
	//private Fecha fecha;
	//private ImporteMonetario importe;
}
';


		if (fwrite($myfile, $txt))
			echo true;
		else
			echo false;

		fclose($myfile);
	}




?>