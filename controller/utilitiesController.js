var app = angular.module("app", []);

app.controller("utilitiesController", function($scope, $http){

	var app = this;

	$scope.cargarTabla = function(nombreArchivo, frecuencia){
		$http.post("http://climacharts.com.mx/php/csvToJson.php", {'nombreArchivo': nombreArchivo, 'frecuenciaArchivo': frecuencia})
		.success(function(data){
			console.log("RESPONSE: " + data.response);
			if(data.response == 0){
					$('#epwData').data('json',data.response);
					document.location.href = "registroIncompleto.html";
			}else{
				alert("Error! No se pudo cargar el archivo CSV.");
			}
		})
		.error(function(data){
			console.log(data);
		});
	}

});
