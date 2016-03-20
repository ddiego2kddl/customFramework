<?php

	$myfile = fopen("utilidades/Utilidades.java", "w") or die("Unable to open file!");

	$txt = 'package '.$sng->paqueteria.'.utilidades;


import java.util.Date;
import java.text.SimpleDateFormat;

/**
 * 
 * Clase de utilidades
 *
 */
public class Utilidades {

	public static long parseStringToLong(String longIn) {
		long longAux = 0;
		try {
			longAux = Long.valueOf(longIn.trim());
		} catch (NumberFormatException e) {
			AbstractControladorSNG_1.throwSNException(
					ConstantesSNG_1.DES_ERROR_FORMATO_NUMERICO_INVALIDO,
					ConstantesSNG_1.COD_ERROR_GENERICO);
		}
		return longAux;
	}
	
	public static ImporteMonetario obtenerImporteMonetario(char cantidadDecimal,
			long importeNominal, Moneda monedaImporte) {
		ImporteMonetario importeBISA = new ImporteMonetario();
		try {
			importeBISA.setImporteConSigno(importeNominal);
			importeBISA.setNumeroDecimalesImporte(cantidadDecimal);
			importeBISA.setMonedaBISA(monedaImporte);
		} catch (WIException e) {
			AbstractControladorSNG_1.throwSNException(
					ConstantesSNG_1.DES_ERROR_FORMATO_IMPORTE_INVALIDO,
					ConstantesSNG_1.COD_ERROR_GENERICO);
		}
		return importeBISA;
	}

	public static ImporteMonetario obtenerImporteMonetario(char cantidadDecimal,
			long importe, String monedaImporte) {
		ImporteMonetario importeBISA = new ImporteMonetario();
		try {
			Moneda moneda = new Moneda();

			moneda.setDivisa(monedaImporte.substring(0, 2));

			if (monedaImporte.length() == 4) {
				moneda.setDigitoControlDivisa(monedaImporte.charAt(3));
			}

			else {
				moneda.setDigitoControlDivisa(ConstantesSNG_1.DIGITO_CONTROL_DIVISA);
			}

			importeBISA.setImporteConSigno(importe);
			importeBISA.setNumeroDecimalesImporte(cantidadDecimal);
			importeBISA.setMonedaBISA(moneda);
		} catch (WIException e) {
			AbstractControladorSNG_1.throwSNException(
					ConstantesSNG_1.DES_ERROR_FORMATO_IMPORTE_INVALIDO,
					ConstantesSNG_1.COD_ERROR_GENERICO);
		}

		return importeBISA;
	}
	
	public static ImporteMonetario crearImporteBisa(int cantidad, char decimales) {
		final ImporteMonetario importe = new ImporteMonetario();
		try {
			importe.setImporteConSigno(cantidad);
			importe.setNumeroDecimalesImporte(decimales);
		} catch (WIException e) {
			AbstractControladorSNG_1.throwSNException(
					ConstantesSNG_1.DES_ERROR_FORMATO_IMPORTE_INVALIDO,
					ConstantesSNG_1.COD_ERROR_GENERICO);
		}
		return importe;
	}
	
	public static CantidadDecimal15 getCantidadFromImportMonetario(ImporteMonetario importeMonetario) {
        CantidadDecimal15 cantidad = new CantidadDecimal15();
		try {
			cantidad.setCantidad(importeMonetario.getImporteConSigno());
        	cantidad.setNumDecimales(String.valueOf(importeMonetario.getNumeroDecimalesImporte()));
		} catch (WIException e) {
			AbstractControladorSNG_1.throwSNException(
					ConstantesSNG_1.DES_ERROR_FORMATO_CANTIDAD_DECIMAL_INVALIDO,
					ConstantesSNG_1.COD_ERROR_GENERICO);
		}
        return cantidad;
    }
	
	public static Fecha crearFechaBisa(String valorFecha)  {
		Fecha fecha = new Fecha();
		try {
			fecha.setValor(valorFecha);
		} catch (WIException e) {
			AbstractControladorSNG_1.throwSNException(
					ConstantesSNG_1.DES_ERROR_FORMATO_FECHA_INVALIDO,
					ConstantesSNG_1.COD_ERROR_GENERICO);
		}
		return fecha;
	}

	public static Porcentaje9 parsePorcentaje15ToPorcentaje9(Porcentaje15 porcentajeToParse) {
		Porcentaje9 porc = new Porcentaje9();
		try {
			porc.setPorcentaje((int) porcentajeToParse.getPorcentaje());
			porc.setNumDecimales(porcentajeToParse.getNumDecimales());
		} catch (WIException e) {
			AbstractControladorSNG_1.throwSNException(
					ConstantesSNG_1.DES_ERROR_FORMATO_PORCENTAJE_INVALIDO,
					ConstantesSNG_1.COD_ERROR_GENERICO);
		}
		return porc;
    }

	public static Porcentaje4 generatePorcentaje4(long porcentaje, String decimales)
            throws WIException {
        Porcentaje4 porcentaje4 = new Porcentaje4();

        porcentaje4.setPorcentaje((short) porcentaje);
        porcentaje4.setNumDecimales(decimales);

        return porcentaje4;
    }

	public static Fecha formatStringToFecha(String fechaEvento) {
        
        Fecha fecha = new Fecha();
        try {
            fecha.setValor(fechaEvento);
        } catch (WIException e) {
            e.printStackTrace();
        }

        return fecha;
    }

	public static CantidadDecimal15 obtenerImporte(final String importeString) throws WIException{
        
        final CantidadDecimal15 importe = new CantidadDecimal15();
        importe.setCantidad(Long.parseLong(importeString.substring(0, 9).trim()));
        importe.setNumDecimales(importeString.substring(9).trim());
        return importe;
        
    }

    public static String today(){
		Date today = new Date();
		SimpleDateFormat format = new SimpleDateFormat("EEEE, d \'de\' MMMM \'de\' yyyy \'a las\' HH:mm \'horas.");
		return format.format(today);
	}

	public static String getImporteString(ImporteMonetario importeNominal) throws WIException {
        //TODO: por el momento fijado a \';\'
        String SEPARADOR_DECIMAL = ",";
        long importeConSigno = importeNominal.getImporteConSigno(); 
        String signo = importeConSigno < 0 ? "-" : "+"; 
        char numDecimalesChar = importeNominal.getNumeroDecimalesImporte();
        
        String importeConDecimal = String.valueOf(importeConSigno);    
        
        // Se le quita el signo si es negativo
        if (signo.equals("-")) {
            importeConDecimal = importeConDecimal.substring(1);
        }
        
        int numDecimales = 0;
        // Si el caracter de los numeros decimales es invalido se usa 0 como numero de decimales
        try {
            numDecimales = Integer.parseInt(String.valueOf(numDecimalesChar));
        } catch (NumberFormatException e) {
            numDecimales = 0;
        }
        
        int numDigitos = importeConDecimal.length();

        String parteEntera = "";
        String parteDecimal = "";
        
        if (numDecimales >= numDigitos) {
            System.out.println("Error: El numero de decimales no puede superar al numero de digitos del importe");
        } else {
            parteEntera = importeConDecimal.substring(0, numDigitos-numDecimales);
            parteDecimal = importeConDecimal.substring(numDigitos-numDecimales, numDigitos);
        }
        
        // En caso de que se especifique añadir EURO se añadirá así
        // Obtenemos el nombre de la divisa (EURO)
        // es.cm.arq.tda.tiposdedatosbase.Moneda m = new
        // es.cm.arq.tda.tiposdedatosbase.Moneda();
        // es.cm.arq.tda.tiposdedatosbase.Moneda m2 = null;
        // EURO: "281"
        // String moneda = "";
        // try {
        // m2 = m.getMonedaPorCodigoInternoSinDC("281");
        // moneda = m2.getNombreCorto();
        // } catch (TipoDeDatoException e) {
        // moneda = "";
        // }
            
        StringBuilder sBuilder = new StringBuilder();
        if (signo.equals("-")) {
            sBuilder.append(signo);
        }
        sBuilder.append(parteEntera);
        if (numDecimales > 0) {
            sBuilder.append(SEPARADOR_DECIMAL);
            sBuilder.append(parteDecimal);
        }
        // En caso de que se especifique añadir EURO se añadirá así
        // sBuilder.append(moneda);
        
        return sBuilder.toString();
    }
    
    public static String getValorTruncado(String nombreCompletoCliente, int maxLength) {
        if (nombreCompletoCliente != null
                && nombreCompletoCliente.length() > maxLength) {
            return nombreCompletoCliente.substring(0, maxLength);
        } else {
            return nombreCompletoCliente;
        }
    }


    public static char parseStringToChar(String charIn) {
        char charAux = \' \';
        if(charIn == null || charIn.length() > 1) {
            AbstractControladorSNG_1.throwSNException(
                    ConstantesSNG_1.DES_ERROR_FORMATO_CARACTER_INVALIDO,
                    ConstantesSNG_1.COD_ERROR_GENERICO);
        } else {
            charAux = charIn.charAt(0);
        }
        return charAux;
    }


    public static boolean parseCharToBoolean(char charIn) {
        if (charIn == \'S\') {
            return true;
        } else if (charIn != \'N\') {
            AbstractControladorSNG_1.throwSNException(
                    ConstantesSNG_1.DES_ERROR_FORMATO_CARACTER_INVALIDO,
                    ConstantesSNG_1.COD_ERROR_GENERICO);
        }
        return false;
    }



    public static CodigoInternacionalCuentaBancaria obtenerCodigoInternacional(String digitoDeControl) {
        CodigoInternacionalCuentaBancaria cuentaDomiciliacion = new CodigoInternacionalCuentaBancaria();
        try {
            cuentaDomiciliacion.setDigitosDeControl(digitoDeControl);
        } catch (WIException e) {
            AbstractControladorSNG_1.throwSNException(
                    ConstantesSNG_1.DES_ERROR_FORMATO_CODIGO_INTERNACIONAL_CUENTA_BANCARIA_INVALIDO,
                    ConstantesSNG_1.COD_ERROR_GENERICO);
        }
        return cuentaDomiciliacion;
    }


    public static String getStringConEspacios(String entradaSinEspacios, int tamanyoTotalConEspacios) {
       
        String entradaSinEspaciosString = entradaSinEspacios;
        int tamanyoSinEspacios = entradaSinEspacios.length();
       
        if(tamanyoSinEspacios < tamanyoTotalConEspacios) {
            StringBuilder sBuilder = new StringBuilder();
            int numSpaces = tamanyoTotalConEspacios - tamanyoSinEspacios;
            for(int i=0; i<numSpaces; i++) {
                sBuilder.append(" ");
            }
            sBuilder.append(entradaSinEspaciosString);
            entradaSinEspaciosString = sBuilder.toString();
        } else if (tamanyoSinEspacios > tamanyoTotalConEspacios) {
            AbstractControladorSNG_1.throwSNException("getStringConEspacios: "+
                    ConstantesSNG_1.DES_ERROR_FORMATO_CARACTER_INVALIDO,
                    ConstantesSNG_1.COD_ERROR_GENERICO);
        }       
       
        return entradaSinEspaciosString;
    }


}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);
?>