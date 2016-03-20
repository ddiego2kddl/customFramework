<?php

	$myfile = fopen("utilidades/ConstantesSNG_1.java", "w") or die("Unable to open file!");

	$txt = 'package '.$sng->paqueteria.'.utilidades;

public abstract class ConstantesSNG_1 {

	// Errores
	public static final String COD_ERROR_GENERICO = "EG0001";

	// TO-DO: asignar el mensaje de error correspondiente
	public static final String DES_ERROR_WI_EXCEPTION_XXXXXXX = "No se ha podido obtener los importes anticipados no vencidos de un expediente confirming.";
	public static final String COD_ERROR_WI_EXCEPTION_XXXXXXX = "00001088";

	public static final String DES_ERROR_OBTENER_DETALLE = "No ha sido posible obtener el detalle del documento de la cartera pendiente del cliente confirming";
	public static final String DES_ERROR_PARAM_ENTRADA = "El campo %s es obligatorio";
	public static final String DES_ERROR_FORMATO_NUMERICO_INVALIDO = "El campo referencia expediente tiene forma";
	public static final String DES_ERROR_NUM_INTER_DOC = "El campo numero interno documento es obligatorio";
	public static final String DES_ERROR_PARAM_ENTRADA_REF_EXP = "El campo referencia expediente es obligatorio";
	public static final String DES_ERROR_FORMATO_IMPORTE_INVALIDO = "El formato del importe es invalido";
	public static final String DES_ERROR_FORMATO_FECHA_INVALIDO = "El formato de la fecha es invalido";
	public static final String DES_ERROR_FORMATO_PORCENTAJE_INVALIDO = "El formato del porcentaje es invalido";
	public static final String DES_ERROR_FORMATO_CANTIDAD_DECIMAL_INVALIDO = "El formato de la cantidad decimal es invalido";
	public static final String DES_ERROR_FORMATO_CARACTER_INVALIDO = "El formato del caracter es invalido";
	public static final String DES_ERROR_FORMATO_CODIGO_INTERNACIONAL_CUENTA_BANCARIA_INVALIDO = "El formato del código internacional de cuenta bancaria es invalido";


	// Cabecera funcional peticion
    public static final String DATA_COFRAQ = "150"; 

	// Mensajes de log
	public static final String DEBUG_INICIO_TRANSFORMAR = "Inicio %s -> %s";
	public static final String DEBUG_FIN_TRANSFORMAR = "Fin %s -> %s";

	// Parámetros entrada
	public static final String PARAM_NUM_INTER_DOC = "numero interno documento";
	public static final String PARAM_REF_EXP = "referencia expediente";

	// Parte específica
	public static final Character INDICADOR_OIE_NOSINANOS = \'O\';
	public static final Character INDICADOR_CONTROL = \'N\';

	// Funciones
	public static final char DIGITO_CONTROL_DIVISA = \'1\';

}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);
?>