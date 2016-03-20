<?php

	$views="";
	for ($i=0; $i<count($sbp->views); $i++) {
		$views.='
	<var name="'.$sbp->views[$i].'"
		class="'.$sbp->subproceso->paqueteria.'.flow.dto.'.$sbp->views[$i].'" />
';
	}


	$models="";
	for ($i=0; $i<count($sbp->models); $i++) {
		$models.='
	<var name="'.$sbp->models[$i].'"
		class="'.$sbp->subproceso->paqueteria.'.flow.dto.'.$sbp->models[$i].'" />
';
	}


	$beans="";
	for ($i=0; $i<count($sbp->sngDaos); $i++) {
		if ($sbp->sngDaos[$i]->beanAuxiliar != null && $sbp->sngDaos[$i]->beanAuxiliar != ""){
			$beans.='
	<var name="'.$sbp->sngDaos[$i]->beanAuxiliar.'"
		class="'.$sbp->subproceso->paqueteria.'.flow.dto.'.$sbp->sngDaos[$i]->beanAuxiliar.'" />
';
		}
	}

	$actionStates = "";
	for ($i=0; $i<count($sbp->actions); $i++) {
		$viewsAction="";
		$trasitionsActions="";
		if ($sbp->actions[$i]->onSuccess !== null && $sbp->actions[$i]->onSuccess->iu->view != ""){
			$viewsAction .= $sbp->actions[$i]->onSuccess->iu->view;
			$trasitionsActions.='
		<transition on="success" to="'.$sbp->actions[$i]->onSuccess->iu->name.'" />';
			if (isset($sbp->actions[$i]->onError))
					$viewsAction  .= ",";
		}
		if (isset($sbp->actions[$i]->onError)){
			$viewsAction  .= $sbp->actions[$i]->onError->iu->view;
			$trasitionsActions.='
		<transition on="error" to="'.$sbp->actions[$i]->onError->iu->name.'" />';
		}


		$actionStates.='
	<action-state id="'.$sbp->actions[$i]->name.'">
		<evaluate expression="consultarLiquidacionesRepartoMargenesAction.'.$sbp->actions[$i]->name.'('.$sbp->actions[$i]->sngDao->beanAuxiliar.', '.$viewsAction.')" />'.$trasitionsActions.'
	</action-state>
';
	}


	$viewState = "";
	for ($i=0; $i<count($sbp->ius); $i++) {
		$viewStateTransitions="";
		$viewStateModel="";
		for ($j=0;$j<count($sbp->ius[$i]->transitions); $j++){
			$viewStateTransitions.='
		<transition on="'.$sbp->ius[$i]->transitions[$j]->action->name.'" to="'.$sbp->ius[$i]->transitions[$j]->action->name.'" />';
			if (isset($sbp->ius[$i]->transitions[$j]->model)){
				if ($viewStateModel=="")
					$viewStateModel.='
		model="\''.$sbp->ius[$i]->transitions[$j]->action->name.':'.$sbp->ius[$i]->transitions[$j]->model.'\'';
				else
					$viewStateModel.=',\''.$sbp->ius[$i]->transitions[$j]->action->name.':'.$sbp->ius[$i]->transitions[$j]->model.'\'';
			}
		}
		if ($viewStateModel!="")
			$viewStateModel.='"';

		$viewState.='
	<view-state id="'.$sbp->ius[$i]->name.'"'.$viewStateModel.'
		view="'.$sbp->ius[$i]->view.'">'.$viewStateTransitions.'
	</view-state>
';
	}
	



//CONSTRUCCION DEL FICHERO CON LAS VARIABLES QU4E SE HAN MONTADO ANTEIRORMENTE
	$myfile = fopen("resources/META-INF/views/".$paqueteria_flow_route."/".$paqueteria_flow.".flow.xml", "w") or die("Unable to open file!");


	$txt = '<?xml version="1.0" encoding="UTF-8"?>

<flow xmlns="http://www.springframework.org/schema/webflow"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.springframework.org/schema/webflow http://www.springframework.org/schema/webflow/spring-webflow-2.0.xsd"
	parent="parent, exception-handler" start-state="'.$sbp->actions[0]->name.'">

	<!-- Views -->'.$views.'

	<!-- Models -->'.$models.'

	<!-- Beans auxiliares -->'.$beans.'

	<!-- *** *** *** *** *** *** *** *** *** -->

	'.$actionStates.'

	'.$viewState.'

</flow>
';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);




?>