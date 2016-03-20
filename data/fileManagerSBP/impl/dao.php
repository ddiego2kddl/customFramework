<?php

	for ($i=0; $i<count($sbp->sngs); $i++) {



			$myfile = fopen("dao/".$sbp->sngs[$i]->name."DAO.java", "w") or die("Unable to open file!");




			$txt = 'package '.$sbp->subproceso->paqueteria.'.dao;

import '.$sbp->subproceso->paqueteria.'.dao.dto.'.$sbp->sngs[$i]->name.'InDTO;
import '.$sbp->subproceso->paqueteria.'.dao.dto.'.$sbp->sngs[$i]->name.'OutDTO;

public interface '.$sbp->sngs[$i]->name.'DAO 
{
    public '.$sbp->sngs[$i]->name.'OutDTO '.$sbp->sngs[$i]->name.' (
    		final '.$sbp->sngs[$i]->name.'InDTO inDTO);
}
';




			if (fwrite($myfile, $txt))
				echo true;
			else
				echo false;

			fclose($myfile);

	}


?>