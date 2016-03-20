<?php
	
	$myfile = fopen("test/CamelRouteListener.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.test;

import java.io.File;
import java.io.FileInputStream;

import org.apache.camel.Exchange;
import org.apache.camel.Processor;
import org.apache.camel.builder.RouteBuilder;
import org.apache.camel.component.file.GenericFile;
import org.apache.camel.model.RoutesDefinition;

/**
 * A Camel Java DSL Router
 */
public class CamelRouteListener extends RouteBuilder {

	/**
	 * Lets configure the Camel routing rules using Java code...
	 */
	@Override
	public void configure() {

		// here is a sample which processes the input files
		// (leaving them in place - see the \'noop\' flag)
		// then performs content based routing on the message using XPath
		from("file:src/routes?noop=true").process(new Processor() {
			public void process(final Exchange e) throws Exception {

				@SuppressWarnings("rawtypes")
				final GenericFile file = (GenericFile) e.getIn().getBody();

				final FileInputStream fis = new FileInputStream((File) file
						.getBody());
				final RoutesDefinition rd = getContext().loadRoutesDefinition(
						fis);
				// getContext().stopRoute(rd.getId());
				// getContext().removeRoute(rd.getId());
				getContext().addRouteDefinitions(rd.getRoutes());
				getContext().startRoute(rd.getId());

			}
		});
	}
}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>