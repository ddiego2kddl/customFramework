<?php

	$myfile = fopen($path_route."/pom.xml", "w") or die("Unable to open file!");

	$txt = '<?xml version="1.0" encoding="utf-8"?>
<project xmlns="http://maven.apache.org/POM/4.0.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 http://maven.apache.org/xsd/maven-4.0.0.xsd">

	<modelVersion>4.0.0</modelVersion>
	<groupId>'.$sng->paqueteria.'</groupId>
	<artifactId>'.$sng->name.'</artifactId>
	<name>route-${project.artifactId}</name>
	<version>1.0.0-SNAPSHOT</version>
	<packaging>jar</packaging>

	<dependencies>
		<!-- INTERFACE SNG -->
	
		<dependency>
			<groupId>org.hamcrest</groupId>
			<artifactId>hamcrest-library</artifactId>
			<version>1.2.1</version>
			<scope>test</scope>
		</dependency>
		<dependency>
			<groupId>dom4j</groupId>
			<artifactId>dom4j</artifactId>
			<version>1.6.1</version>
			<scope>test</scope>
		</dependency>
		<dependency>
			<groupId>jaxen</groupId>
			<artifactId>jaxen</artifactId>
			<version>1.1.1</version>
			<scope>test</scope>
		</dependency>
		<dependency>
			<groupId>org.reflections</groupId>
			<artifactId>reflections</artifactId>
			<version>0.9.8</version>
			<scope>test</scope>
		</dependency>
		<dependency>
			<groupId>org.mockito</groupId>
			<artifactId>mockito-all</artifactId>
			<version>1.9.5</version>
		</dependency>

		<!-- testing Para generar de pruebas unitarias a partir de ficheros JSON -->
		<dependency>
			<groupId>com.googlecode.json-simple</groupId>
			<artifactId>json-simple</artifactId>
			<version>1.1.1</version>
			<scope>test</scope>
		</dependency>
	</dependencies>
	<build>
		<finalName>${project.name}-${project.version}</finalName>
		<plugins>
			<plugin>
				<groupId>org.apache.maven.plugins</groupId>
				<artifactId>maven-compiler-plugin</artifactId>
				<version>3.0</version>
				<configuration>
					<compilerVersion>1.5</compilerVersion>
					<source>1.5</source>
					<target>1.5</target>
					<encoding>UTF-8</encoding>
				</configuration>
			</plugin>
			<plugin>
				<groupId>org.apache.maven.plugins</groupId>
				<artifactId>maven-jar-plugin</artifactId>
				<version>2.4</version>
				<configuration>
					<finalName>route-${project.artifactId}</finalName>
				</configuration>
			</plugin>
			<plugin>
				<groupId>org.apache.maven.plugins</groupId>
				<artifactId>maven-install-plugin</artifactId>
				<executions>
					<execution>
						<id>install-jar</id>
						<phase>install</phase>
						<goals>
							<goal>install-file</goal>
						</goals>
						<configuration>
							<packaging>jar</packaging>
							<artifactId>${project.artifactId}</artifactId>
							<groupId>${project.groupId}</groupId>
							<version>${project.version}</version>
							<file>
								${project.build.directory}/route-${project.artifactId}.jar</file>
							<archive>
								<addMavenDescriptor>false</addMavenDescriptor>
								<manifestEntries>
									<Built-By>GFI</Built-By>
									<Version>
										${project.name}-${project.version}</Version>
								</manifestEntries>
							</archive>
						</configuration>
					</execution>
				</executions>
			</plugin>
			<plugin>
				<groupId>org.apache.maven.plugins</groupId>
				<artifactId>maven-assembly-plugin</artifactId>
				<version>2.4</version>
				<executions>
					<execution>
						<id>build-zip</id>
						<phase>package</phase>
						<goals>
							<goal>assembly</goal>
						</goals>
					</execution>
				</executions>
				<configuration>
					<finalName>${project.artifactId}</finalName>
					<appendAssemblyId>false</appendAssemblyId>
					<descriptors>
						<descriptor>/src/assemble/assemble.xml</descriptor>
					</descriptors>
				</configuration>
			</plugin>
			<plugin>
				<groupId>org.codehaus.mojo</groupId>
				<artifactId>exec-maven-plugin</artifactId>
				<version>1.1</version>
				<configuration>
					<mainClass>com.custom.sn.examples.MainApp</mainClass>
				</configuration>
			</plugin>
		</plugins>
	</build>
	<distributionManagement>
		<repository>
			<id>releases</id>
			<name>Releases</name>
			<url>${repo.releases}</url>
		</repository>
		<snapshotRepository>
			<id>snapshots</id>
			<name>Snapshots</name>
			<url>${repo.snapshots}</url>
		</snapshotRepository>
	</distributionManagement>
	<parent>
		<groupId>com.custom.sn</groupId>
		<artifactId>pom-agrupador</artifactId>
		<version>1.0</version>
	</parent>
	<description>'.$sng->description.'</description>
</project>';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>