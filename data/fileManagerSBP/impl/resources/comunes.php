<?php


	$myfile = fopen("resources/ehcache.xml", "w") or die("Unable to open file!");


	$txt = '<?xml version="1.0" encoding="UTF-8"?>
<ehcache xsi:noNamespaceSchemaLocation="ehcache.xsd" updateCheck="true" monitoring="autodetect" dynamicConfig="true">
	<diskStore path="java.io.tmpdir" />
	<defaultCache eternal="false" maxElementsInMemory="10000" overflowToDisk="true" diskPersistent="false"
		timeToIdleSeconds="0" timeToLiveSeconds="0" memoryStoreEvictionPolicy="LRU" />
</ehcache>
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




		$myfile = fopen("resources/o2.properties", "w") or die("Unable to open file!");


	$txt = 'user=
responsibleOrganizationalUnit=000017740098
distributionChannel=0371
localApplication=O2
dispatcherAgentCode=&WEB
clientProgramCode=AQ
sourceType=71
userTerminal=AA
productId=AA
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);


	



		$myfile = fopen("resources/midtr2x.properties", "w") or die("Unable to open file!");


	$txt = 'midtr2x.url=http://sliro526:31485/bisa/endpoint
midtr2x.plataforma=IOI
midtr2x.entorno=PU
midtr2x.configurationType=CLIENT
midtr2x.transportType=HTTP
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




		$myfile = fopen("resources/log4j.xml", "w") or die("Unable to open file!");


	$txt = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE log4j:configuration SYSTEM "log4j.dtd">
<log4j:configuration xmlns:log4j="http://jakarta.apache.org/log4j/">
	<appender class="org.apache.log4j.ConsoleAppender" name="CONSOLE">
		<layout class="org.apache.log4j.PatternLayout">
			<param
				value=">>>>%d{yyyy-MM-dd\'T\'HH:mm:ss,SSSZ}|INSTANCIA:%X{instancia}:%X{puerto}|IE:%X{IE}|COD:%X{COD}|CABARQ:%X{CABARQ}|%m|%nSEV:%5p|"
				name="ConversionPattern" />
		</layout>
	</appender>

	<root>
		<priority value="trace" />
		<appender-ref ref="CONSOLE" />
	</root>
</log4j:configuration>
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




		$myfile = fopen("resources/ioi.properties", "w") or die("Unable to open file!");


	$txt = 'ambar.entity.code=2038
translation.service.endpoint=MOCK

';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>