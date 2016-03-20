<?php


	$myfile = fopen($path_route."/impl/pom.xml", "w") or die("Unable to open file!");


	$webInfLibText="";
	$sngText="";
    for ($i=0; $i<count($sbp->sngs); $i++) {
        $sngText .= '		<dependency>
		  	<groupId>'.$sbp->sngs[$i]->groupId.'</groupId>
		  	<artifactId>'.$sbp->sngs[$i]->artifactId.'</artifactId>
		  	<version>'.$sbp->sngs[$i]->version.'</version>
		</dependency>
';
		if($webInfLibText!=""){
			$webInfLibText.=',
';
		}
		$webInfLibText.='								WEB-INF/lib/'.$sbp->sngs[$i]->name.'*.jar';
    }

    $daoText="";
    for ($i=0; $i<count($sbp->daos); $i++) {
        $daoText .= '		<dependency>
		  	<groupId>'.$sbp->daos[$i]->groupId.'</groupId>
		  	<artifactId>'.$sbp->daos[$i]->artifactId.'</artifactId>
		  	<version>'.$sbp->daos[$i]->version.'</version>
		</dependency>
';
		if($webInfLibText!=""){
			$webInfLibText.=',
';
		}
		$webInfLibText.='								WEB-INF/lib/'.$sbp->sngs[$i]->name.'*.jar';
    }


	$txt = '<project xmlns="http://maven.apache.org/POM/4.0.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 http://maven.apache.org/xsd/maven-4.0.0.xsd">
	<modelVersion>4.0.0</modelVersion>
	<parent>
		<groupId>'.$sbp->subproceso->paqueteria.'.flow</groupId>
		<artifactId>'.$sbp->subproceso->name.'-flow-parent</artifactId>
		<version>1.0.0-SNAPSHOT</version>
	</parent>

	<artifactId>'.$sbp->subproceso->name.'-flow</artifactId>
	<packaging>war</packaging>

	<properties>
		<channel-adapter.version>1.0.0-SNAPSHOT</channel-adapter.version>
		<config-manager.version>1.0.0-SNAPSHOT</config-manager.version>
		<execution-context.version>1.0.0-SNAPSHOT</execution-context.version>
		<flows-core.version>1.0.1-SNAPSHOT</flows-core.version>
		<bs-invoker.version>1.0.0-SNAPSHOT</bs-invoker.version>
	</properties>

	<dependencies>

		<!-- Dependencias de subprocesos -->
		<dependency>
			<groupId>com.custom.arq.ioi.commons</groupId>
			<artifactId>channel-adapter</artifactId>
			<version>${channel-adapter.version}</version>
		</dependency>
		<dependency>
			<groupId>com.custom.arq.ioi.commons</groupId>
			<artifactId>config-manager</artifactId>
			<version>${config-manager.version}</version>
		</dependency>
		<dependency>
			<groupId>com.custom.arq.ioi.commons</groupId>
			<artifactId>execution-context</artifactId>
			<version>${execution-context.version}</version>
		</dependency>
		<dependency>
			<groupId>com.custom.arq.ioi.flows</groupId>
			<artifactId>flows-core</artifactId>
			<version>${flows-core.version}</version>
		</dependency>

		<!-- Cache -->
		<dependency>
			<groupId>net.sf.ehcache</groupId>
			<artifactId>ehcache</artifactId>
		</dependency>




		<!-- Test -->
		<dependency>
			<groupId>junit</groupId>
			<artifactId>junit-dep</artifactId>
		</dependency>
		<dependency>
			<groupId>org.hamcrest</groupId>
			<artifactId>hamcrest-core</artifactId>
		</dependency>
		<dependency>
			<groupId>org.hamcrest</groupId>
			<artifactId>hamcrest-library</artifactId>
		</dependency>
		<dependency>
			<groupId>org.mockito</groupId>
			<artifactId>mockito-all</artifactId>
		</dependency>
		<dependency>
			<groupId>org.springframework</groupId>
			<artifactId>spring-test</artifactId>
		</dependency>
		<dependency>
			<groupId>com.custom.ioi</groupId>
			<artifactId>test-helper</artifactId>
			<version>1.0.0-SNAPSHOT</version>
		</dependency>

		<!-- Dependencias propias del proyecto -->
		<dependency>
			<groupId>com.custom.arq.ioi.commons</groupId>
			<artifactId>enrichment-providers</artifactId>
			<version>1.0.23-SNAPSHOT</version>
		</dependency>

		<!-- SNG -->
'.$sngText.'

		<!-- DAOs -->
'.$daoText.'

	</dependencies>

	<!-- El objetivo de este war skinny es meramente contener las dependencias 
		específicas de nuestro SBP. Así, por un lado se desplegará este WAR skinny 
		y por otro el WAR con nuestras clases, prescindiendo de la sobrecargada carpeta 
		WEB-INF/lib -->
	<build>
		<plugins>
			<plugin>
				<groupId>org.apache.maven.plugins</groupId>
				<artifactId>maven-surefire-plugin</artifactId>
				<configuration>
					<testFailureIgnore>true</testFailureIgnore>
				</configuration>
			</plugin>
			<plugin>
				<groupId>org.apache.maven.plugins</groupId>
				<artifactId>maven-dependency-plugin</artifactId>
				<executions>
					<execution>
						<phase>compile</phase>
						<goals>
							<goal>copy-dependencies</goal>
						</goals>
						<configuration>
							<outputDirectory>${project.build.directory}/lib</outputDirectory>
						</configuration>
					</execution>
				</executions>
			</plugin>
			<plugin>
				<groupId>org.apache.maven.plugins</groupId>
				<artifactId>maven-war-plugin</artifactId>
				<executions>
					<execution>
						<id>full</id>
						<goals>
							<goal>war</goal>
						</goals>
					</execution>
					<execution>
						<id>skinny</id>
						<goals>
							<goal>war</goal>
						</goals>
						<configuration>
							<packagingIncludes> %regex[WEB-INF/(?!.*.jar).*],
'.$webInfLibText.'				
							</packagingIncludes>
							<warName>${project.build.finalName}</warName>
							<classifier>SKINNY</classifier>
						</configuration>
					</execution>
				</executions>
			</plugin>
		</plugins>
		<pluginManagement>
			<plugins>
				<!--This plugins configuration is used to store Eclipse m2e settings only. It has no influence on the Maven build itself.-->
				<plugin>
					<groupId>org.eclipse.m2e</groupId>
					<artifactId>lifecycle-mapping</artifactId>
					<version>1.0.0</version>
					<configuration>
						<lifecycleMappingMetadata>
							<pluginExecutions>
								<pluginExecution>
									<pluginExecutionFilter>
										<groupId>
											org.apache.maven.plugins
										</groupId>
										<artifactId>
											maven-dependency-plugin
										</artifactId>
										<versionRange>
											[2.7,)
										</versionRange>
										<goals>
											<goal>
												copy-dependencies
											</goal>
										</goals>
									</pluginExecutionFilter>
									<action>
										<ignore></ignore>
									</action>
								</pluginExecution>
							</pluginExecutions>
						</lifecycleMappingMetadata>
					</configuration>
				</plugin>
			</plugins>
		</pluginManagement>
	</build>

</project>';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>