<?php

	////////////////////////////////////
	//.classpath////////////////////////
	////////////////////////////////////
	$myfile = fopen($path_route."/.classpath", "w") or die("Unable to open file!");

	$txt = '<?xml version="1.0" encoding="UTF-8"?>
<classpath>
	<classpathentry kind="src" output="target/classes" path="src/main/java">
		<attributes>
			<attribute name="optional" value="true"/>
			<attribute name="maven.pomderived" value="true"/>
		</attributes>
	</classpathentry>
	<classpathentry excluding="**" kind="src" output="target/classes" path="src/main/resources">
		<attributes>
			<attribute name="maven.pomderived" value="true"/>
		</attributes>
	</classpathentry>
	<classpathentry kind="src" output="target/test-classes" path="src/test/java">
		<attributes>
			<attribute name="optional" value="true"/>
			<attribute name="maven.pomderived" value="true"/>
		</attributes>
	</classpathentry>
	<classpathentry kind="con" path="org.eclipse.jdt.launching.JRE_CONTAINER/org.eclipse.jdt.internal.debug.ui.launcher.StandardVMType/J2SE-1.5">
		<attributes>
			<attribute name="maven.pomderived" value="true"/>
		</attributes>
	</classpathentry>
	<classpathentry kind="con" path="org.eclipse.m2e.MAVEN2_CLASSPATH_CONTAINER">
		<attributes>
			<attribute name="maven.pomderived" value="true"/>
		</attributes>
	</classpathentry>
	<classpathentry kind="output" path="target/classes"/>
</classpath>';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);


	////////////////////////////////////
	//pom.xml  ////////////////////////
	////////////////////////////////////

	include 'pomXML.php';


	////////////////////////////////////
	//.project////////////////////////
	////////////////////////////////////

	$myfile = fopen($path_route."/.project", "w") or die("Unable to open file!");

	$txt = '<?xml version="1.0" encoding="UTF-8"?>
<projectDescription>
	<name>'.$sng->name.'</name>
	<comment></comment>
	<projects>
	</projects>
	<buildSpec>
		<buildCommand>
			<name>org.eclipse.jdt.core.javabuilder</name>
			<arguments>
			</arguments>
		</buildCommand>
		<buildCommand>
			<name>org.eclipse.m2e.core.maven2Builder</name>
			<arguments>
			</arguments>
		</buildCommand>
	</buildSpec>
	<natures>
		<nature>org.eclipse.jdt.core.javanature</nature>
		<nature>org.eclipse.m2e.core.maven2Nature</nature>
	</natures>
</projectDescription>';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);


	////////////////////////////////////
	//assemble.xml////////////////////////
	////////////////////////////////////

	$assemble_route = $path_route."/src/assemble";
	if (!file_exists($assemble_route)) {
		mkdir($assemble_route,0777,true);
	}

	$myfile = fopen($assemble_route."/assemble.xml", "w") or die("Unable to open file!");

	$txt = '<assembly
	xmlns="http://maven.apache.org/plugins/maven-assembly-plugin/assembly/1.1.2"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://maven.apache.org/plugins/maven-assembly-plugin/assembly/1.1.2 http://maven.apache.org/xsd/assembly-1.1.2.xsd">

	<id>${project.artifactId}</id>
	<formats>
		<format>zip</format>
	</formats>
	<includeBaseDirectory>false</includeBaseDirectory>
	<fileSets>
		<fileSet>
			<directory>target</directory>
			<outputDirectory>/</outputDirectory>
			<includes>
				<include>route-${project.artifactId}.jar</include>
			</includes>
		</fileSet>
		<fileSet>
			<directory>src/routes</directory>
			<outputDirectory>/</outputDirectory>
			<includes>
				<include>**/*</include>
			</includes>
		</fileSet>
	</fileSets>
</assembly>';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);


	////////////////////////////////////
	//route.xml//////////////////////
	////////////////////////////////////

	$routes_route = $path_route."/src/routes";
	if (!file_exists($routes_route)) {
		mkdir($routes_route,0777,true);
	}

	$myfile = fopen($routes_route."/route-".$sng->name.".xml", "w") or die("Unable to open file!");

	$txt = '<?xml version="1.0" encoding="utf-8"?>
<routes xmlns="http://camel.apache.org/schema/spring">
	<route id="start'.$sng->name.'_1">
		<description>'.$sng->description.'
		</description>
		<from
			uri="direct://start'.$sng->name.'_1" />
		<bean ref="OrquestationInit" method="init" beanType="OrquestationInit" />
		<doTry>
			<to
				uri="direct://obtener_'.$sng->name.'_1" />
			<bean
				ref="'.$sng->name.'_1_Controlador"
				method="eliminarReferenciaServicioNegocio(${body.getcabeceraNegocio.getIdUnicoServicioNegocio})"
				beanType="'.$sng->name.'_1_Controlador"
				id="'.$sng->name.'_1_Controlador_eliminarReferenciaSNG" />
			<to uri="mock:NULL?RetainFirst=1" />
			<doCatch>
				<exception>java.lang.Exception</exception>
				<handled>
					<constant>true</constant>
				</handled>
				<bean
					ref="'.$sng->name.'_1_Controlador"
					method="errorInternoSNG(${exception},${body.getcabeceraNegocio.getIdUnicoServicioNegocio})"
					beanType="'.$sng->name.'_1_Controlador"
					id="'.$sng->name.'_1_Controlador_exception" />
				<to uri="mock:NULL?RetainFirst=1" />
			</doCatch>
		</doTry>
	</route>

	<route id="obtener_'.$sng->name.'_1">
		<from
			uri="direct://obtener_'.$sng->name.'_1" />
		<to
			uri="direct://validarEntrada_'.$sng->name.'_1" />
		<to
			uri="direct://ejecutar_'.$sng->dependencia.'_'.$sng->name.'_1" />
	</route>

	<route
		id="validarEntrada_'.$sng->name.'_1">
		<from
			uri="direct://validarEntrada_'.$sng->name.'_1" />
		<bean
			ref="'.$sng->name.'_1_Controlador"
			method="transform"
			beanType="'.$sng->name.'_1_Controlador"
			id="'.$sng->name.'_1_Controlador_Validar" />
	</route>

	<route
		id="ejecutar_'.$sng->dependencia.'_'.$sng->name.'_1">
		<from
			uri="direct://ejecutar_'.$sng->dependencia.'_'.$sng->name.'_1" />
		<bean
			ref="'.$sng->name.'_1_TransformIn_'.$sng->dependencia.'"
			method="transform"
			beanType="'.$sng->name.'_1_TransformIn_'.$sng->dependencia.'"
			id="'.$sng->name.'_1_TransformIn_'.$sng->dependencia.'" />
		<to
			uri="direct://ejecutar_'.$sng->name.'_1" />
		<bean
			ref="'.$sng->name.'_1_TransformOut_'.$sng->dependencia.'"
			method="transform"
			beanType="'.$sng->name.'_1_TransformOut_'.$sng->dependencia.'"
			id="'.$sng->name.'_1_TransformOut_'.$sng->dependencia.'" />
	</route>

	<route id="ejecutar_'.$sng->name.'_1">
		<from
			uri="direct://ejecutar_'.$sng->name.'_1" />
		<doTry>
			<bean ref="Midtr2invoke" method="process" beanType="Midtr2invoke"
				id="execute'.$sng->name.'_1" />
			<doCatch>
				<exception>java.lang.Exception</exception>
				<handled>
					<constant>true</constant>
				</handled>
				<bean
					ref="'.$sng->name.'_1_Controlador"
					method="errorInvocacionSNG(${exception},${body.getcabeceraNegocio.getIdUnicoServicioNegocio})"
					beanType="'.$sng->name.'_1_Controlador"
					id="'.$sng->name.'_1_Controlador_exceptionTratar" />
			</doCatch>
		</doTry>
	</route>
</routes>';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);



?>