'use strict';

app.service('subProcesoService', function($http,$q){
   
	this.makeSBP = function(sbp){
		return $http.post('data/fileManagerSBP/makeSBP.php',sbp);
	}

});