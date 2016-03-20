<?php


	$myfile = fopen($path_route."/impl/pom.xml", "w") or die("Unable to open file!");


	$txt = '';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>