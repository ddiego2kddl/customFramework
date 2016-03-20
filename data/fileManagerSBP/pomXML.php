<?php

	$myfile = fopen($path_route."/pom.xml", "w") or die("Unable to open file!");




	$txt = '<?xml version="1.0" encoding="UTF-8"?>
<project xmlns="http://maven.apache.org/POM/4.0.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 http://maven.apache.org/xsd/maven-4.0.0.xsd">
  <modelVersion>4.0.0</modelVersion>
  <parent>
    <groupId>com.custom.ioi</groupId>
    <artifactId>pom-agrupador</artifactId>
    <version>1.0</version>
  </parent>
  <groupId>'.$sbp->subproceso->paqueteria.'.flow</groupId>
  <artifactId>'.$sbp->subproceso->name.'-flow-parent</artifactId>
  <version>1.0.0-SNAPSHOT</version>
  <packaging>pom</packaging>
  <modules>
    <module>itests</module>
    <module>impl</module>
  </modules>
  <description />
</project>';

	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>