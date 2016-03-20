<?php

	$mainResources_route = $path_route."/src/main/resources";
	if (!file_exists($mainResources_route)) {
		mkdir($mainResources_route,0777,true);
	}

	////////////////////////////////////
	//log4j.properties////////////////////////
	////////////////////////////////////
	$myfile = fopen($mainResources_route."/log4j.properties", "w") or die("Unable to open file!");

	$txt = "#-------------------------------------------------------------------------------
# CATEGORIA DE APLICACION
# 
log4j.category.org.apache.camel=error,appenderSistema
log4j.category.portal=debug,appenderSistema
log4j.category.org.apache.commons.httpclient=error,appenderSistema
log4j.category.httpclient=error,appenderSistema


#Sistema
log4j.appender.appenderSistema=org.apache.log4j.RollingFileAppender
log4j.appender.appenderSistema.file=./log/api-sistema.log
log4j.appender.appenderSistema.layout=org.apache.log4j.PatternLayout
#log4j.appender.appenderSistema.layout.ConversionPattern=%d %-5p %C{1} [%x] : %m%n
log4j.appender.appenderSistema.layout.ConversionPattern=\>>>>%d{yyyy-MM-dd'T'HH:mm:ss,SSSZ}\|%m|SEV:%5p\%n|INSTANCIA: %X{instancia}%X{puerto} |IE:%X{elemento} |COD:%X{idelemento} |CABARQ:%X{cabeceras} |

log4j.additivity.appenderSistema=false

log4j.appender.appenderSistema.maxBackupIndex=3
log4j.appender.appenderSistema.maxFileSize=20MB

#Aplicacion
log4j.appender.appenderApl=org.apache.log4j.RollingFileAppender
log4j.appender.appenderApl.file=./log/api-aplicacion.log

log4j.appender.appenderApl.layout=org.apache.log4j.PatternLayout
log4j.additivity.appenderApl=false
#log4j.appender.appenderApl.layout.ConversionPattern=%d %-5p %C{1} [%x] : %m%n
log4j.appender.appenderSistema.layout.ConversionPattern=\>>>>%d{yyyy-MM-dd'T'HH:mm:ss,SSSZ}\|%m|SEV:%5p\%n|INSTANCIA: %X{instancia}%X{puerto} |IE:%X{elemento} |COD:%X{idelemento} |CABARQ:%X{cabeceras} |

log4j.appender.appenderApl.maxBackupIndex=3
log4j.appender.appenderApl.maxFileSize=20MB";


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);


	////////////////////////////////////
	//propiedades.properties////////////////////////
	////////////////////////////////////
	$myfile = fopen($mainResources_route."/propiedades.properties", "w") or die("Unable to open file!");

	$txt = '
alias=@in.canalDistribucion

#Datos de usuario comunes a los servicios
datosUsuario.numeroCliente=@in.IdentificadorContrato
datosUsuario.numeroUsuario=@in.usuario
datosUsuario.idSesionWL=0';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);


	////////////////////////////////////
	//CHANGE ROUTER --> META-INF////////
	////////////////////////////////////

	$metaInf_route = $mainResources_route."/META-INF";
	if (!file_exists($metaInf_route)) {
		mkdir($metaInf_route,0777,true);
	}

	////////////////////////////////////
	//ejb-jar.xml////////////////////////
	////////////////////////////////////

	$myfile = fopen($metaInf_route."/ejb-jar.xml", "w") or die("Unable to open file!");

	$txt = '<?xml version="1.0" encoding="UTF-8"?>
<ejb-jar id="BusinessConnectorEJB" version="2.1"
	xmlns="http://java.sun.com/xml/ns/j2ee" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://java.sun.com/xml/ns/j2ee http://java.sun.com/xml/ns/j2ee/ejb-jar_2_1.xsd">
	<display-name>BusinessConnectorEJB</display-name>
	<enterprise-beans>
		<session>
			<ejb-name>BusinessConnectorEJB</ejb-name>
			<home>com.gfi.webIntegrator.connector.GenericExternConnectorHome</home>
			<remote>com.gfi.webIntegrator.connector.GenericExternConnector</remote>
			<ejb-class>es.cajamadrid.bisa.businessconnector.BusinessConnectorBean</ejb-class>
			<session-type>Stateless</session-type>
			<transaction-type>Container</transaction-type>
		</session>
	</enterprise-beans>
	<assembly-descriptor>
		<container-transaction>
			<method>
				<ejb-name>BusinessConnectorEJB</ejb-name>
				<method-name>*</method-name>
			</method>
			<trans-attribute>NotSupported</trans-attribute>
		</container-transaction>
	</assembly-descriptor>
</ejb-jar>';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

	////////////////////////////////////
	//weblogic-ejb-jar.xml//////////////
	////////////////////////////////////

	$myfile = fopen($metaInf_route."/weblogic-ejb-jar.xml", "w") or die("Unable to open file!");

	$txt = '<?xml version="1.0"?>
<!DOCTYPE weblogic-ejb-jar PUBLIC "-//BEA Systems, Inc.//DTD WebLogic 8.1.0 EJB//EN" "http://www.bea.com/servers/wls810/dtd/weblogic-ejb-jar.dtd">
<weblogic-ejb-jar>
	<weblogic-enterprise-bean>
		<ejb-name>BusinessConnectorEJB</ejb-name>
		<stateless-session-descriptor>
			<pool>
				<max-beans-in-free-pool>40</max-beans-in-free-pool>
				<initial-beans-in-free-pool>0</initial-beans-in-free-pool>
			</pool>
		</stateless-session-descriptor>
		<jndi-name>BusinessConnector</jndi-name>
	</weblogic-enterprise-bean>
</weblogic-ejb-jar>';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);


	////////////////////////////////////
	//MANIFEST.MF///////////////////////
	////////////////////////////////////

	$myfile = fopen($metaInf_route."/MANIFEST.MF", "w") or die("Unable to open file!");

	$txt = 'Manifest-Version: 1.0
Class-Path: 
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);


?>