$(document).ready(function() {

	var container = $('h1').after($('<div/>').addClass('container main'));
	
	$('div.main').append($('<div/>').addClass('row').attr('id', 'chart'));
  	
	//d3.select('#chart').append('svg').attr("id", "psychChart").style({'width': '100%', 'height': '100%'});
	
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++){
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == "edo"){
			params = sParameterName[1].split('-');
			estado = params[0];
			tipo = params[1];
		}
	}

	var chart = new psychrometricChart(estado, tipo);
	//var data = d3.csv.parse(d3.select("#epw").text());
	//console.log(data);


	var epw_file = obtieneNombreArchivoEPW(estado);

	d3.text(epw_file, function(data) {
		d3.select("#chart").datum(formatEpw(data)).call(chart);
	});

	$(window).resize(chart.resize);


});



  	
  





