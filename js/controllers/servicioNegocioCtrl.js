'use strict';
//***** Controller de la Tabla ******/////
app.controller('servicioNegocioCtrl', function($scope,servicioNegocioService,$window) {
	var auxNameProject = "";
    
    $scope.fileWrite = function() { 
		servicioNegocioService.makeBasicStruct($scope.sng).then(function(rsp){
			console.log(rsp.data);
			$scope.sngMaked = true;
			$scope.sngModified = true;
			auxNameProject = angular.copy($scope.sng.name);
		})
	}

	$scope.fileDownload = function(){
		$window.location.href=('data/fileDownload.php?fileName='+$scope.sng.name+".zip");
	}

	var initProvisional = function(){
		$scope.sng.name = "ObtenerCarterasPendientesExpConfirmingClienteSNG";
		$scope.sng.version = "1.0";
		$scope.sng.paqueteria = "com.bankia.sn.confirmingcliente.carterapendientes.consultar";
		$scope.sng.dependencia = "CQPSC003_INS";
		$scope.sng.dependenciaCommand = "mvn install:install-file -DgroupId=com.bankia.sn -DartifactId="+$scope.sng.name+" -Dversion=1.0 -Dpackaging=jar -Dfile="+$scope.sng.name+".jar";
		$scope.sng.description = "Obtiene la cartera de expedientes de Confirming cliente";
		$scope.sng.dependenciaVersion = "1.0"
	}

	$scope.$watch('sng', function(newVal, oldVal){
    	$scope.sngModified = false;
	}, true);

	$scope.$watch('sng.paqueteria', function(newVal, oldVal){
    	$scope.ioiContents = false;
    	if ($scope.sng.paqueteria != null)
	    	if ($scope.sng.paqueteria.indexOf("ioi")>-1)
	    			$scope.ioiContents = true;
	}, true);


	var init = function(){
		$scope.sng = {};
		$scope.sngMaked = false;
		$scope.sngModified = false;
		$scope.ioiContents = false;
		//initProvisional();
	}

	$scope.$watch('sng.name + sng.version', function() {
       $scope.sng.dependenciaCommand = "mvn install:install-file -DgroupId=com.bankia.sn -DartifactId="+$scope.sng.name+" -Dversion="+$scope.sng.version+" -Dpackaging=jar -Dfile="+$scope.sng.name+".jar";
   	});
	
    
    init();
});