<?php
	
	$myfile = fopen("resources/WiServiceClassMock.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.resources;


public class WiServiceClassMock extends WIService {
	public WiServiceClassMock(String aModule, String aName) throws WIException {
		super(aModule, aName);
	}

	public WiServiceClassMock() throws WIException {
		super(null, null);
	}
}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>