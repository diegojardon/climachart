function cargarTabla(nombreArchivo){
    var url = "http://climacharts.com.mx/php/csvToJson.php";
    var data = {nombreArchivo: nombreArchivo};

    fetch(url, {
        method: 'POST',
        body: JSON.stringify(data),
        headers:{
            'Content-Type': 'application/json'
        }
    }).then(res => res.json())
    .catch(error => console.error('Error:', error))
    .then(response => {
        console.log('Exito:', response)
        generaTabla(response);
    });
}

function generaTabla(data){
    var col = [];
    for (var i = 0; i < data.info.length; i++) {
        for (var key in data.info[i]) {
            if (col.indexOf(key) === -1) {
                if(key == "name"){
                    col[0] = key;
                }
                if(key == "units"){
                    col[2] = key;    
                }
                if(key == "data"){
                    col[1] = key;    
                }
            }
        }
    }

    // Crear tabla dinámica
    var table = document.createElement("table");
    table.setAttribute("id", "csvData");
    table.setAttribute("class", "table table-bordered table-hover");

    // Crear el header de la tabla con los header obtenidos anteriormente

    var tr = table.insertRow(-1);                   // Fila de la tabla

    for (var i = 0; i < col.length; i++) {
        var th = document.createElement("th");      // Header de la tabla
        th.setAttribute("class", "headerTablaCSV");
        if(col[i] == "name"){
            th.innerHTML = "Medias Mensuales";
            tr.appendChild(th);
        }
        if(col[i] == "units"){
            th.innerHTML = "Unidades";
            tr.appendChild(th);
        }
        if(col[i] == "data"){
            for(var j = 0; j< 12; j++){
                th = document.createElement("th");
                th.setAttribute("class", "headerTablaCSVMeses");
                th.innerHTML = obtieneMes(j);
                tr.appendChild(th);
            }

        }
        
    }

    // Agregar la información del Json como filas en la tabla
    for (var i = 0; i < data.info.length; i++) {
        if(i != 2){
            //Se omite la fila Depression (Avg Daily)
            tr = table.insertRow(-1);
            for (var j = 0; j < col.length; j++) {
                    
                    if(col[j] == "data"){
                        for(var k = 0; k < 12; k++){
                            var tabCell = tr.insertCell(-1);
                            tabCell.innerHTML = parseFloat(data.info[i][col[j]][k]).toFixed(1);
                            tabCell.setAttribute("class", "cellsTablaCSVMeses");
                        }
                    }else{
                        var tabCell = tr.insertCell(-1);
                        tabCell.innerHTML = traduceTituloFilas(data.info[i][col[j]]);
                        tabCell.setAttribute("style", "font-weight: bold;");
                        tabCell.setAttribute("class", "headerTablaCSV");
                    }
            
            }
        }
    }

    var divContainer = document.getElementById("csvData");
    divContainer.innerHTML = "";
    divContainer.appendChild(table);
}

function traduceTituloFilas(tituloFila){
    switch(tituloFila){
        case "Dry Bulb Temp (Avg Daily)":
            return "Temperatura de bulbo seco (promedio mensual)";
        break;
        case "Wet Bulb Temp (Avg Daily)":
            return "Temperatura de bulbo húmedo (promedio mensual)";
        break;
        case "Dew Point (Avg Daily)":
            return "Punto de rocío (promedio mensual)";
        break;
        case "Rel Humidity (Avg Daily)":
            return "Humedad relativa (promedio mensual)";
        break;
        case "Global Horiz Rad (Avg Daily)":
            return "Radiación global horizontal (promedio horas)";
        break;
        case "Direct Norm Rad (Avg Daily)":
            return "Radiación directa normal (promedio horas)";
        break;
        case "Diffuse Rad (Avg Daily)":
            return "Radiación difusa normal (promedio horas)";
        break;
        case "Wind Speed (Avg Daily)":
            return "Velocidad del viento (promedio mensual)";
        break;
        case "Wind Direction (Avg Daily)":
            return "Dirección del viento (promedio mensual)";
        break;
        case "Sky Cover (Avg Daily)":
            return "Nubosidad Unidad";
        break;
        case "degrees C":
            return "°C";
        break;
        case "percent":
            return "%";
        break;
        case "(Wh/sq.m)":
            return "Wh/m2";
        break;
        case "ms":
            return "m/s";
        break;
        case "degrees":
            return "Grados";
        break;
    }
    return tituloFila;
}

function obtieneMes(numMes){
    switch(numMes){
        case 0:
            return "Ene";
        break;
        case 1:
            return "Feb";
        break;
        case 2:
            return "Mar";
        break;
        case 3:
            return "Abr";
        break;
        case 4:
            return "May";
        break;
        case 5:
            return "Jun";
        break;
        case 6:
            return "Jul";
        break;
        case 7:
            return "Ago";
        break;
        case 8:
            return "Sep";
        break;
        case 9:
            return "Oct";
        break;
        case 10:
            return "Nov";
        break;
        case 11:
            return "Dic";
        break;
    }

}

function obtieneMesEspañol(numMes){
    switch(numMes){
        case 1:
            return "ENERO";
        break;
        case 2:
            return "FEBRERO";
        break;
        case 3:
            return "MARZO";
        break;
        case 4:
            return "ABRIL";
        break;
        case 5:
            return "MAYO";
        break;
        case 6:
            return "JUNIO";
        break;
        case 7:
            return "JULIO";
        break;
        case 8:
            return "AGOSTO";
        break;
        case 9:
            return "SEPTIEMBRE";
        break;
        case 10:
            return "OCTUBRE";
        break;
        case 11:
            return "NOVIEMBRE";
        break;
        case 12:
            return "DICIEMBRE";
        break;
    }

}

function MaysPrimera(string){
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function transformaEstado(estado){
    switch(estado){
        case "BajaNor":
            return "Baja California Norte";
        break;
        case "BajaSur":
            return "Baja California Sur";
        break;
        case "Coah":
            return "Coahuila";
        break;
        case "Chih":
            return "Chihuahua";
        break;
        case "Dura":
            return "Durango";
        break;
        case "Sina":
            return "Sinaloa";
        break;
        case "Sono":
            return "Sonora";
        break;
        case "Zaca":
            return "Zacatecas";
        break;
        case "NvoL":
            return "Nuevo León";
        break;
        case "SanLu":
            return "San Luis Potosí";
        break;
        case "Tama":
            return "Tamaulipas";
        break;
        case "Aguas":
            return "Aguascalientes";
        break;
        case "Coli":
            return "Colima";
        break;
        case "Jali":
            return "Jalisco";
        break;
        case "Micho":
            return "Michoacán";
        break;
        case "Naya":
            return "Nayarit";
        break;
        case "Camp":
            return "Campeche";
        break;
        case "Oaxa":
            return "Oaxaca";
        break;
        case "Pueb":
            return "Puebla";
        break;
        case "Tabas":
            return "Tabasco";
        break;
        case "Tlax":
            return "Tlaxcala";
        break;
        case "Cdmx":
            return "Ciudad de México";
        break;
        case "Guana":
            return "Guanajuato";
        break;
        case "Guer":
            return "Guerrero";
        break;
        case "Hida":
            return "Hidalgo";
        break;
        case "EdoMex":
            return "Estado de México";
        break;
        case "More":
            return "Morelos";
        break;
        case "Quere":
            return "Querétaro";
        break;
        case "Vera":
            return "Veracruz";
        break;
        case "Chia":
            return "Chiapas";
        break;
        case "Qroo":
            return "Quintana Roo";
        break;
        case "Yuca":
            return "Yucatán";
        break;
    }
}

function obtieneNombreArchivoEPW(estado){
  var epw_raw_prefix = "MEX_";
  var epw_raw_sufix = "_MX2015.epw";
  var epw_raw_path = "../../../../epw/";
  var ep_raw_name = "";
    switch(estado){
        case "BajaNor":
            ep_raw_name = "MEXICALI";
        break;
        case "BajaSur":
            ep_raw_name = "LA-PAZ";
        break;
        case "Coah":
            ep_raw_name = "SALTILLO";
        break;
        case "Chih":
            ep_raw_name = "CHIHUAHUA";
        break;
        case "Dura":
            ep_raw_name = "DURANGO";
        break;
        case "Sina":
            ep_raw_name = "CULIACAN";
        break;
        case "Sono":
            ep_raw_name = "HERMOSILLO";
        break;
        case "Zaca":
            ep_raw_name = "ZACATECAS";
        break;
        case "NvoL":
            ep_raw_name = "MONTERREY";
        break;
        case "SanLu":
            ep_raw_name = "SAN-LUIS-POTOSI";
        break;
        case "Tama":
            ep_raw_name = "CIUDAD-VICTORIA";
        break;
        case "Aguas":
            ep_raw_name = "AGUASCALIENTES";
        break;
        case "Coli":
            ep_raw_name = "COLIMA";
        break;
        case "Jali":
            ep_raw_name = "GUADALAJARA";
        break;
        case "Micho":
            ep_raw_name = "MORELIA";
        break;
        case "Naya":
            ep_raw_name = "TEPIC";
        break;
        case "Camp":
            ep_raw_name = "CAMPECHE";
        break;
        case "Oaxa":
            ep_raw_name = "OAXACA";
        break;
        case "Pueb":
            ep_raw_name = "PUEBLA";
        break;
        case "Tabas":
            ep_raw_name = "VILLAHERMOSA";
        break;
        case "Tlax":
            ep_raw_name = "TLAXCALA";
        break;
        case "Cdmx":
            ep_raw_name = "CDMX";
        break;
        case "Guana":
            ep_raw_name = "GUANAJUATO";
        break;
        case "Guer":
            ep_raw_name = "CHILPANCINGO";
        break;
        case "Hida":
            ep_raw_name = "PACHUCA";
        break;
        case "EdoMex":
            ep_raw_name = "TOLUCA";
        break;
        case "More":
            ep_raw_name = "CUERNAVACA";
        break;
        case "Quere":
            ep_raw_name = "QUERETARO";
        break;
        case "Vera":
            ep_raw_name = "VERACRUZ";
        break;
        case "Chia":
            ep_raw_name = "TUXTLA-GUTIERREZ";
        break;
        case "Qroo":
            ep_raw_name = "CHETUMAL";
        break;
        case "Yuca":
            ep_raw_name = "MERIDA";
        break;
    }

    return epw_raw_path + epw_raw_prefix + ep_raw_name + epw_raw_sufix;
}

function obtieneNombreArchivoCSV(estado, frecuencia){
  var csv_sufix = "-MENSUAL.csv";
  var csv_path = "../csv/" + frecuencia + "/";
  var csv_name = "";
    switch(estado){
        case "BajaNor":
            csv_name = "MEXICALI";
        break;
        case "BajaSur":
            csv_name = "LA-PAZ";
        break;
        case "Coah":
            csv_name = "SALTILLO";
        break;
        case "Chih":
            csv_name = "CHIHUAHUA";
        break;
        case "Dura":
            csv_name = "DURANGO";
        break;
        case "Sina":
            csv_name = "CULIACAN";
        break;
        case "Sono":
            csv_name = "HERMOSILLO";
        break;
        case "Zaca":
            csv_name = "ZACATECAS";
        break;
        case "NvoL":
            csv_name = "MONTERREY";
        break;
        case "SanLu":
            csv_name = "SAN-LUIS-POTOSI";
        break;
        case "Tama":
            csv_name = "CIUDAD-VICTORIA";
        break;
        case "Aguas":
            csv_name = "AGUASCALIENTES";
        break;
        case "Coli":
            csv_name = "COLIMA";
        break;
        case "Jali":
            csv_name = "GUADALAJARA";
        break;
        case "Micho":
            csv_name = "MORELIA";
        break;
        case "Naya":
            csv_name = "TEPIC";
        break;
        case "Camp":
            csv_name = "CAMPECHE";
        break;
        case "Oaxa":
            csv_name = "OAXACA";
        break;
        case "Pueb":
            csv_name = "PUEBLA";
        break;
        case "Tabas":
            csv_name = "VILLAHERMOSA";
        break;
        case "Tlax":
            csv_name = "TLAXCALA";
        break;
        case "Cdmx":
            csv_name = "CDMX";
        break;
        case "Guana":
            csv_name = "GUANAJUATO";
        break;
        case "Guer":
            csv_name = "CHILPANCINGO";
        break;
        case "Hida":
            csv_name = "PACHUCA";
        break;
        case "EdoMex":
            csv_name = "TOLUCA";
        break;
        case "More":
            csv_name = "CUERNAVACA";
        break;
        case "Quere":
            csv_name = "QUERETARO";
        break;
        case "Vera":
            csv_name = "VERACRUZ";
        break;
        case "Chia":
            csv_name = "TUXTLA-GUTIERREZ";
        break;
        case "Qroo":
            csv_name = "CHETUMAL";
        break;
        case "Yuca":
            csv_name = "MERIDA";
        break;
    }

    return csv_path + csv_name + csv_sufix;
}