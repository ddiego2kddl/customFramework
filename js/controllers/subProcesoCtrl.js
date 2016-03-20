'use strict';

app.controller('subProcesoCtrl', ['$scope','$modal','subProcesoService', function($scope,$modal,subProcesoService){

	function lowerFirstLetter(string) {
    	return string.charAt(0).toLowerCase() + string.slice(1);
	}

	$scope.$watch('sbp.subproceso.name', function() {
    	$scope.sbp.subproceso.nameLow= lowerFirstLetter($scope.sbp.subproceso.name);
   	}, true);


   	$scope.$watch('[sbp.sngs,sbp.daos]', function() {
        for (var i=0; i<$scope.sbp.sngs.length ; i++){
        	if ($scope.sbp.sngs[i].rellamada){
        		$scope.sbp.sngs[i].beanAuxiliar = "BeanAuxiliar"+$scope.sbp.sngs[i].name;
        	}else{
                $scope.sbp.sngs[i].beanAuxiliar = null;
            }
        }

        for (var i=0; i<$scope.sbp.daos.length ; i++){
        	if ($scope.sbp.daos[i].rellamada){
        		$scope.sbp.daos[i].beanAuxiliar = "BeanAuxiliar"+$scope.sbp.daos[i].name;
        	}else{
                $scope.sbp.sngs[i].beanAuxiliar = null;
            }
        }

        $scope.sbp.sngDaos=angular.copy($scope.sbp.sngs.concat(angular.copy($scope.sbp.daos)));
    }, true);


    function makeDAO(name,rellamada,version,groupId,artifactId){
    	var dao = {};
    	dao.name = name;
    	dao.rellamada = rellamada;
    	dao.version = version;
    	dao.groupId = groupId;
    	dao.artifactId = artifactId;
    	return dao;
    }

    function makeSNG(name,rellamada,version,groupId,artifactId){
    	var sng = {};
    	sng.name = name;
    	sng.rellamada = rellamada;
    	sng.version = version;
    	sng.groupId = groupId;
    	sng.artifactId = artifactId;
    	return sng;
    }

    function mock(){
    	$scope.sbp.subproceso.name = "ConsultarLiqRepartoMargenConfirmingCliente";
    	$scope.sbp.subproceso.paqueteria = "com.bankia.ioi.financiacion.confirmingcliente.repartomargenes.consultar";

    	$scope.sbp.models = ["DatosObtenerLiquidacionesRepartoMargenesModel"];
    	$scope.sbp.infos = ["ExpedienteConfirmingCliente","LiquidacionRepartoMargenes"];
    	$scope.sbp.views = ["ExpedientesConfirmingClienteView","DatosMostrarLiquidacionesRepartoMargenesView"];

    	$scope.sbp.daos.push(makeDAO("ObtenerExpedientesConfirmingCliente",true,"1.0.0-SNAPSHOT","com.bankia.ioi.financiacion.confirmingcliente.expedientes.consultar.dao","ObtenerExpedientesConfirmingCliente-dao"));

    	$scope.sbp.sngs.push(makeSNG("ObtenerLiqRepartoMargenConfirmingCliente",true,"1.0","com.bankia.sn","ObtenerLiqRepartoMargenesConfirmingClienteSNG"));

    	
    	$scope.sbp.actions = [{name:"obtenerExpedientes",sngDao:$scope.sbp.daos[0]},
    						{name:"obtenerMasExpedientes",sngDao:$scope.sbp.daos[0]},
    						{name:"obtenerLiquidacionesRepartoMargenes",sngDao:$scope.sbp.sngs[0]},
    						{name:"obtenerMasLiquidacionesRepartoMargenes",sngDao:$scope.sbp.sngs[0]}];
    	$scope.sbp.ius = [{name:"SolicitarIdentificadorExpedienteConfirmingIU",view:"ExpedientesConfirmingClienteView"},{name:"MostrarLiquidacionesRepartoMargenesIU",view:"DatosMostrarLiquidacionesRepartoMargenesView"}];
    }

    function init(){
    	$scope.sbp = {};
    	$scope.sbp.daos = [];
    	$scope.sbp.sngs = [];
    	$scope.sbp.sngDaos=[];
    	$scope.sbp.infos = [];
    	$scope.sbp.views = [];
    	$scope.sbp.beans = [];
    	$scope.sbp.models = [];
    	$scope.sbp.actions = [];
    	$scope.sbp.ius = [];
    	$scope.sbp.subproceso = {};
    	$scope.transitionSelected = {};
    	
    	mock();

    }

    init();

    $scope.makeDAO = function(indexDAO){
    	var auxDAO=null;
    	if (indexDAO!=null){
    		auxDAO = $scope.sbp.daos[indexDAO];
    	}
        var modalInstance = $modal.open({
            templateUrl: 'partials/subProceso/modalDAO.html',
            controller: 'daoAddModalCtrl',
            windowClass: 'transpModal',
            backdrop: false,
            size: 'lg',
            resolve: {
                daoInfo: function(){
                    return auxDAO;
                }
            }
        });
        modalInstance.result.then(function(selectedItem){
        	if (indexDAO==null)
        		$scope.sbp.daos.push(selectedItem);
        	else
        		$scope.sbp.daos[indexDAO]= selectedItem;
        });
    };

    $scope.removeDAO = function(indexDAO){
    	$scope.sbp.daos.splice(indexDAO,1);
    }


    $scope.makeSNG = function(sngIndex){
    	var auxSNG=null;
    	if (sngIndex!=null){
    		auxSNG = $scope.sbp.sngs[sngIndex];
    	}
        var modalInstance = $modal.open({
            templateUrl: 'partials/subProceso/modalSNG.html',
            controller: 'sngAddModalCtrl',
            windowClass: 'transpModal',
            backdrop: false,
            size: 'lg',
            resolve: {
                sngInfo: function(){
                    return auxSNG;
                }
            }
        });
        modalInstance.result.then(function(selectedItem){
        	if (sngIndex==null){
        		$scope.sbp.sngs.push(selectedItem);
        	}
        	else{
        		$scope.sbp.sngs[sngIndex] = selectedItem;
        	}
        });
    };

    $scope.removeSNG = function(indexSNG){
    	$scope.sbp.sngs.splice(indexSNG,1);
    }

    $scope.addView = function(){
    	$scope.sbp.views.push($scope.sbp.newView);
    	$scope.sbp.newView = "";
    }

    $scope.removeView = function(indexView){
		$scope.sbp.views.splice(indexView, 1);
    }

    $scope.addModel = function(){
    	$scope.sbp.models.push($scope.sbp.newModel);
    	$scope.sbp.newModel = "";
    }

    $scope.removeModel = function(indexModel){
		$scope.sbp.models.splice(indexModel, 1);
    }

    $scope.addInfo = function(){
    	$scope.sbp.infos.push($scope.sbp.newInfo);
    	$scope.sbp.newInfo = "";
    }

    $scope.removeInfo = function(indexInfo){
		$scope.sbp.infos.splice(indexInfo, 1);
    }

    //iu asociadas a views
    $scope.addIu = function(){
    	if (!$scope.newIu.name){
    		alert("tienes que darle un nombre al IU");
    	}
    	else if (!$scope.newIu.view){
    		alert("selecciona un VIEW asociada al IU");
    	}else{
    		$scope.newIu.transitions=[];
    		$scope.sbp.ius.push(angular.copy($scope.newIu));
    		$scope.newIu.name = "";
    	}
    	
    }

    $scope.removeIu = function(indexIu){
		$scope.sbp.ius.splice(indexIu, 1);
    }


    $scope.addTransition = function(iu){
    	var newTransition = {};
    	newTransition.action = angular.copy($scope.transitionSelected.action);
    	newTransition.model = angular.copy($scope.transitionSelected.model);
    	if (!iu.transitions)
    		iu.transitions=[];
    	iu.transitions.push(newTransition);
    	$scope.transitionSelected = {};
    }

    $scope.removeTransition = function(indexTransition,iu){
    	iu.transitions.splice(indexTransition,1);
    }

    //actions asociados a SNGS
    $scope.addAction = function(){
    	if (!$scope.newAction.name){
    		alert("tienes que darle un nombre al ACTION");
    	}else if (!$scope.newAction.sngDao){
    		alert("selecciona un SNG DAO asociada al ACTION");
    	}else{
    		$scope.newAction.onError = {};
    		$scope.newAction.onError.value=false;
    		$scope.newAction.onSuccess ={};
    		$scope.newAction.onSuccess.value=false;
    		$scope.sbp.actions.push(angular.copy($scope.newAction));
    		$scope.newAction.name = "";
    	}
    }

    $scope.removeAction = function(indexAction){
		$scope.sbp.actions.splice(indexAction, 1);
    }


    //writeFile
    $scope.fileWrite = function(){
        crearArrayParaActionJava();
    	console.log("-- $scope.sbp ---");
    	console.log($scope.sbp);
    	console.log("******************** START SERVICE ********************");
    	subProcesoService.makeSBP($scope.sbp).then(function(rsp){
			console.log(rsp.data);
			console.log("******************** END SERVICE ********************");
		})
    }

    function crearArrayParaActionJava(){
        $scope.sbp.arrayActionsAux = [];

        //añado variable de control
        for (var i=0; i<$scope.sbp.actions.length;i++)   
            $scope.sbp.actions[i].catalogado = false;

        //gestiono el nuevo array para construir el action.java
        for (var i=0; i<$scope.sbp.actions.length;i++){
            console.log($scope.sbp.actions[i]);
            //si ya esta calogado no entramos mas
            if ($scope.sbp.actions[i].catalogado == false){
                //si tiene rellama hay que buscar su pareja
                if ($scope.sbp.actions[i].sngDao.rellamada){
                    console.log($scope.sbp.actions[i].name+" :rellamda")
                     $scope.sbp.arrayActionsAux.push(aparejarRellamadas($scope.sbp.actions[i]));
                }
                //sino tiene pareja se mete sin mas
                else{
                    $scope.sbp.arrayActionsAux.push(angular.copy($scope.sbp.actions[i]));
                    $scope.sbp.actions[i].catalogado = true;
                }
                console.log($scope.sbp.arrayActionsAux);
            }
        }

        for (var i=0; i<$scope.sbp.arrayActionsAux.length; i++){
            for (var j=0; j<$scope.sbp.ius.length; j++){
                console.log(1);
                console.log($scope.sbp.ius[j].outDTO.name);
                console.log($scope.sbp.arrayActionsAux[i].sngDao.name);
                if ($scope.sbp.ius[j].outDTO.name == $scope.sbp.arrayActionsAux[i].sngDao.name){
                    console.log(2),
                    $scope.sbp.arrayActionsAux[i].view = $scope.sbp.ius[j].view;
                    j=j<$scope.sbp.ius.length+1;
                }
            }
        }
    }


    function aparejarRellamadas(actionI){
        for (var j=0; j<$scope.sbp.actions.length;j++){
            if ($scope.sbp.actions[j].catalogado == false){
                if ($scope.sbp.actions[j].sngDao == actionI.sngDao && $scope.sbp.actions[j].name != actionI.name){
                    var auxAction = angular.copy($scope.sbp.actions[j]);
                    if (auxAction.name.length<actionI.name.length)
                        auxAction.nameRellamada= actionI.name;
                    else{
                        auxAction.nameRellamada= auxAction.name;
                        auxAction.name = actionI.name;
                    }
                    actionI.catalogado=true;
                    $scope.sbp.actions[j].catalogado=true;
                    console.log(auxAction);
                    return auxAction;
                }
            }
        }
    }

}]);


////////////////////////////////////////////////
////***** CONTROLLER MODAL SNG NUEVO ******/////
////////////////////////////////////////////////
app.controller('sngAddModalCtrl', function($state, $scope, $modalInstance, sngInfo) {

	if (sngInfo){
		$scope.newSNG = angular.copy(sngInfo);
		var backSNGINFO = angular.copy(sngInfo);
	}
	else{
		$scope.newSNG = {};
		$scope.newSNG.rellamada=false;
	}

    $scope.guardarSNG=function(){
        $modalInstance.close($scope.newSNG);
    };

    $scope.cancel= function(){
    	if (backSNGINFO){
    		$scope.newSNG  = backSNGINFO;
        	$modalInstance.close($scope.newSNG);
    	}
    	else{
    		$modalInstance.dismiss('cancel');
    	}
    }
});



////////////////////////////////////////////////
////***** CONTROLLER MODAL DAO NUEVO ******/////
////////////////////////////////////////////////
app.controller('daoAddModalCtrl', function($state, $scope, $modalInstance, daoInfo) {

	if (daoInfo){
		$scope.newDAO = angular.copy(daoInfo);
		var backDAOINFO = angular.copy(daoInfo);
	}
	else{
		$scope.newDAO = {};
		$scope.newDAO.rellamada=false;
	}

    $scope.guardarDAO=function(){
        $modalInstance.close($scope.newDAO);
    };

    $scope.cancel= function(){
    	if (backDAOINFO){
    		console.log(backDAOINFO);
    		$scope.newDAO  = backDAOINFO;
        	$modalInstance.close($scope.newDAO);
    	}
    	else{
    		$modalInstance.dismiss('cancel');
    	}
    }
});