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
            ep_raw_name = "";
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
            ep_raw_name = "";
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
            ep_raw_name = "";
        break;
        case "Chia":
            ep_raw_name = "TUXTLA-GUTIERREZ";
        break;
        case "Qroo":
            ep_raw_name = "CHETUMAL";
        break;
        case "Yuca":
            ep_raw_name = "";
        break;
    }

    return epw_raw_path + epw_raw_prefix + ep_raw_name + epw_raw_sufix;
}