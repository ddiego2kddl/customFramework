'use strict';

app.service('servicioNegocioService', function($http,$q){
	
	this.makeBasicStruct = function(sng){
		return $http.post('data/fileManagerSNG/makeSNG.php',sng);
	}

	this.downloadFileBD = function(fileName){
		return $http.get('data/fileDownload.php?fileName='+fileName);
	}

});

