<?php

	$basePackage = "";


	for ($i=0; $i<count($sbp->daos); $i++) {
		$basePackage.= '
					  '.$sbp->daos[$i]->groupId.'.dao,';

	}

	$daoMocks = "";
	for ($i=0; $i<count($sbp->sngDaos); $i++) {
		$daoMocks.= '<bean id="'.$sbp->sngDaos[$i]->name.'DAO" 
			class="com.custom.ioi.mock.commons.dao.'.$sbp->sngDaos[$i]->name.'DAOMock"/>
		';

	}


	$myfile = fopen("resources/META-INF/spring/app-context.xml", "w") or die("Unable to open file!");


	$txt = '<?xml version="1.0" encoding="UTF-8"?>
<beans xmlns="http://www.springframework.org/schema/beans"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:context="http://www.springframework.org/schema/context"
	xmlns:p="http://www.springframework.org/schema/p" xmlns:conf="http://ioi.custom.com/schema/configurations"
	xmlns:util="http://www.springframework.org/schema/util" xmlns:cache="http://www.springframework.org/schema/cache"
	xmlns:aop="http://www.springframework.org/schema/aop"
    http://ioi.custom.com/schema/configurations http://ioi.custom.com/schema/configurations.xsd">

	<bean name="executionContextHolder"
		class="com.custom.arq.ioi.commons.execution.context.ExecutionContextHolderMockUsingCustomizedMockSessions" />

	<import resource="classpath*:/META-INF/spring/bs-invoker-context.xml" />

	<conf:config-manager id="configurationManager"
		xmlns="http://ioi.custom.com/schema/configurations">
		<dbproperties-location name="midtr2x" fileName="midtr2x.properties"
			refreshTime="1000" applicationAlias="IOI" clearOnNotify="false" />
		<dbproperties-location name="o2" fileName="o2.properties"
			refreshTime="1000" applicationAlias="IOI" clearOnNotify="false" />
		<dbproperties-location name="ioi" fileName="ioi.properties"
			refreshTime="1000" applicationAlias="IOI" clearOnNotify="false" />
	</conf:config-manager>

	<context:component-scan
		base-package="'.$sbp->subproceso->paqueteria.'.flow,
					  '.$sbp->subproceso->paqueteria.'.dao,'.$basePackage.'
					  com.custom.ioi.mock.commons.dao,
					  com.custom.ioi.commons.channeladapter"/>

	<aop:aspectj-autoproxy expose-proxy="true" />
	<cache:annotation-driven />

	<beans profile="test">

		<context:property-placeholder
			ignore-resource-not-found="false" ignore-unresolvable="false"
			system-properties-mode="OVERRIDE" />

		<conf:config-manager id="configurationManager"
			xmlns="http://ioi.custom.com/schema/configurations">
			<file-location name="midtr2x">
				<path type="filesystem" value="${test.properties.location}/midtr2x.properties" />
			</file-location>
			<file-location name="o2">
				<path type="filesystem" value="${test.properties.location}/o2.properties" />
			</file-location>
			<file-location name="ioi">
				<path type="filesystem" value="${test.properties.location}/ioi.properties" />
			</file-location>
		</conf:config-manager>

		<bean name="apiSesionGestion"
			class="com.custom.arq.scc.fake.client.FakeApiSesionGestionCustomizedMockSessions" />

		<bean id="executionContextHolder"
      	 	class="com.custom.arq.ioi.commons.execution.context.ExecutionContextHolderMockUsingCustomizedMockSessions"/>
	
		<!-- DAO Mocks -->
		'.$daoMocks.'
	
		
	</beans>
</beans>
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>